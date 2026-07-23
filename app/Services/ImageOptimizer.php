<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizer
{
    /**
     * Compress and save uploaded image directly into public/images/ directory.
     * Temporarily boosts PHP memory limit for GD processing, resizes ultra-large
     * camera photos to 1600px max, and falls back cleanly without 500 memory errors.
     */
    public static function optimize($file, string $directory, string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        // Temporarily boost memory limit for GD image conversion
        @ini_set('memory_limit', '512M');

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
                // Downscale ultra-high-resolution images (max 1600px) to save RAM and disk space
                $width = imagesx($image);
                $height = imagesy($image);
                $maxDimension = 1600;

                if ($width > $maxDimension || $height > $maxDimension) {
                    if ($width >= $height) {
                        $newWidth = $maxDimension;
                        $newHeight = (int) round(($height / $width) * $maxDimension);
                    } else {
                        $newHeight = $maxDimension;
                        $newWidth = (int) round(($width / $height) * $maxDimension);
                    }

                    $resized = imagescale($image, $newWidth, $newHeight);
                    if ($resized) {
                        imagedestroy($image);
                        $image = $resized;
                    }
                }

                // Preserve transparency for PNG/WebP
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                // Write 1 single WebP file directly to public/images/
                $success = @imagewebp($image, $fullPath, $quality);
                imagedestroy($image);

                if ($success && file_exists($fullPath)) {
                    $storageFullPath = storage_path('app/public/' . $relativePath);
                    $storageDir = dirname($storageFullPath);
                    if (!is_dir($storageDir)) {
                        @mkdir($storageDir, 0755, true);
                    }
                    @copy($fullPath, $storageFullPath);

                    return $relativePath;
                }
            }
        } catch (\Throwable $e) {
            Log::error("Image optimization failed: " . $e->getMessage());
        }

        // Fallback: standard direct file upload to public/images/ if GD memory/conversion fails
        $fallbackFilename = uniqid() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
        $fallbackRelative = 'images/' . $cleanDir . '/' . $fallbackFilename;
        $fallbackFull = public_path($fallbackRelative);

        $dir = dirname($fallbackFull);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        @copy($realPath, $fallbackFull);

        $fallbackStorageFull = storage_path('app/public/' . $fallbackRelative);
        $fallbackStorageDir = dirname($fallbackStorageFull);
        if (!is_dir($fallbackStorageDir)) {
            @mkdir($fallbackStorageDir, 0755, true);
        }
        @copy($fallbackFull, $fallbackStorageFull);

        return $fallbackRelative;
    }
}
