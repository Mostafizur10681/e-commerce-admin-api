<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait UploadImageTrait
{
    /**
     * Convert and optimize an uploaded image to a base64 Data URI string.
     */
    protected function uploadImage(UploadedFile $file, string $folder = 'products', int $width = 800, int $height = 800): string
    {
        try {
            if (class_exists(ImageManager::class) && class_exists(Driver::class)) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Scale down nicely within max dimensions
                if (method_exists($image, 'scaleDown')) {
                    $image->scaleDown($width, $height);
                } elseif (method_exists($image, 'scale')) {
                    $image->scale($width, $height);
                }

                // Encode to clean WebP or JPEG Data URI for optimized database storage
                if (method_exists($image, 'toWebp')) {
                    return (string) $image->toWebp(85)->toDataUri();
                } elseif (method_exists($image, 'toJpeg')) {
                    return (string) $image->toJpeg(85)->toDataUri();
                }
            }
        } catch (\Throwable $e) {
            // Fallback to direct raw base64 encode
        }

        // Direct raw Base64 Data URI fallback
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        return 'data:' . $mime . ';base64,' . $base64;
    }

    /**
     * Validate and ensure base64 image has proper data URI prefix.
     */
    protected function uploadBase64Image(string $base64Data, string $folder = 'products', int $width = 600, int $height = 600): string
    {
        if (!preg_match('/^data:image\/\w+;base64,/', $base64Data)) {
            $base64Data = 'data:image/jpeg;base64,' . $base64Data;
        }
        return $base64Data;
    }

    /**
     * Delete image helper (safe for both file paths and base64 strings).
     */
    protected function deleteImage(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // If it is a base64 data URI, nothing to delete on disk
        if (str_starts_with($path, 'data:image/')) {
            return true;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
