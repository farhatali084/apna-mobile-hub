<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizer
{
    /**
     * Compress and save uploaded image file using native GD library.
     */
    public static function optimize($file, string $directory, string $disk = 'public'): ?string
    {
        if (!$file) {
            return null;
        }

        $realPath = $file->getRealPath();
        $mime = $file->getMimeType();

        // Ensure target directory exists on disk
        Storage::disk($disk)->makeDirectory($directory);

        // Force WebP extension for all compressed images (Industry standard for web)
        $extension = 'webp';
        $filename = uniqid() . '.' . $extension;
        $targetPath = $directory . '/' . $filename;
        $fullTargetPath = Storage::disk($disk)->path($targetPath);

        // WebP compression quality (0-100)
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
                // Keep transparency intact during WebP conversion
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                // Write as WebP format with 75% quality
                $success = @imagewebp($image, $fullTargetPath, $quality);
                imagedestroy($image);

                if ($success) {
                    return $targetPath;
                }
            }
        } catch (\Throwable $e) {
            Log::error("Image optimization failed: " . $e->getMessage());
        }

        // Fallback: standard file upload with original extension if GD conversion fails
        $fallbackFilename = uniqid() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
        return $file->storeAs($directory, $fallbackFilename, $disk);
    }
}
