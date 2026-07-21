<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizer
{
    /**
     * Compress and save uploaded image directly into public/images/ directory.
     * Saves ONLY 1 single WebP file directly in web-accessible images folder
     * eliminating symlink requirements and avoiding duplicate files.
     */
    public static function optimize($file, string $directory, string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        $realPath = $file->getRealPath();
        $mime = $file->getMimeType();

        // Unique WebP filename in public/images/
        $cleanDir = trim($directory, '/');
        $filename = uniqid() . '.webp';
        $relativePath = 'images/' . $cleanDir . '/' . $filename;
        $fullPath = public_path($relativePath);

        // Ensure public/images/{directory} folder exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // WebP compression quality (75%)
        $quality = 75;

        try {
            $image = null;
            if (str_contains($mime, 'jpeg') || str_contains($mime, 'jpg')) {
                $image = @imagecreatefromjpeg($realPath);
            } elseif (str_contains($mime, 'png')) {
                $image = @imagecreatefrompng($realPath);
            } elseif (str_contains($mime, 'webp')) {
                $image = @imagecreatefromwebp($realPath);
            } elseif (str_contains($mime, 'gif')) {
                $image = @imagecreatefromgif($realPath);
            }

            if ($image) {
                // Preserve transparency for PNG/WebP
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                // Write ONLY 1 single WebP file directly to public/images/
                $success = @imagewebp($image, $fullPath, $quality);
                imagedestroy($image);

                if ($success) {
                    return $relativePath;
                }
            }
        } catch (\Throwable $e) {
            Log::error("Image optimization failed: " . $e->getMessage());
        }

        // Fallback: single upload to public/images/ if GD fails
        $fallbackFilename = uniqid() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
        $fallbackRelative = 'images/' . $cleanDir . '/' . $fallbackFilename;
        $fallbackFull = public_path($fallbackRelative);

        $dir = dirname($fallbackFull);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        @move_uploaded_file($realPath, $fallbackFull);
        return $fallbackRelative;
    }
}
