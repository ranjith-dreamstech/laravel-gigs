<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    private const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'txt'];
    private const MAX_FILE_SIZE = 10485760; // 10MB in bytes

    /**
     * Upload a single file with security validation
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $oldFileName
     * @param array $allowedTypes
     * @throws ValidationException
     * @return string|null
     */
    public function uploadFile(
        UploadedFile $file, 
        string $path = 'uploads', 
        ?string $oldFileName = null,
        array $allowedTypes = null
    ): ?string {
        $this->validateFile($file, $allowedTypes);
        
        $activeDisk = config('filesystems.default');

        if ($file->isValid()) {
            // Delete old file if exists
            if ($oldFileName && Storage::disk($activeDisk)->exists($oldFileName)) {
                Storage::disk($activeDisk)->delete($oldFileName);
            }

            $filename = $this->generateSecureFilename($file);
            $fullPath = $path . '/' . $filename;
            
            $file->storeAs($path, $filename, $activeDisk);

            return $fullPath;
        }

        return null;
    }

    /**
     * Upload multiple files
     *
     * @param array $files
     * @param string $path
     * @param array $allowedTypes
     * @return array
     */
    public function uploadMultipleFiles(
        array $files,
        string $path = 'uploads',
        array $allowedTypes = null
    ): array {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $filePath = $this->uploadFile($file, $path, null, $allowedTypes);
                if ($filePath) {
                    $uploadedFiles[] = $filePath;
                }
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @param array|null $allowedTypes
     * @throws ValidationException
     */
    private function validateFile(UploadedFile $file, ?array $allowedTypes = null): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw ValidationException::withMessages([
                'file' => 'File size cannot exceed ' . $this->formatFileSize(self::MAX_FILE_SIZE)
            ]);
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = $allowedTypes ?? array_merge(self::ALLOWED_IMAGE_TYPES, self::ALLOWED_DOCUMENT_TYPES);
        
        if (!in_array($extension, $allowedTypes)) {
            throw ValidationException::withMessages([
                'file' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes)
            ]);
        }

        // Check MIME type for additional security
        $mimeType = $file->getMimeType();
        if (!$this->isValidMimeType($mimeType, $extension)) {
            throw ValidationException::withMessages([
                'file' => 'Invalid file type detected.'
            ]);
        }
    }

    /**
     * Generate secure filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $hash = hash('sha256', $file->getContent());
        $timestamp = time();
        
        return substr($hash, 0, 16) . '_' . $timestamp . '.' . $extension;
    }

    /**
     * Validate MIME type against extension
     *
     * @param string $mimeType
     * @param string $extension
     * @return bool
     */
    private function isValidMimeType(string $mimeType, string $extension): bool
    {
        $validMimeTypes = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'txt' => ['text/plain'],
        ];

        return isset($validMimeTypes[$extension]) && 
               in_array($mimeType, $validMimeTypes[$extension]);
    }

    /**
     * Format file size in human readable format
     *
     * @param int $bytes
     * @return string
     */
    public function formatFileSize(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor(log($bytes, 1024));
        
        return sprintf('%.2f', $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
    }

    /**
     * Get file details with URL
     *
     * @param string|null $filePath
     * @param string $default
     * @return array
     */
    public function getFileDetails(string|null $filePath, string $default = 'default'): array
    {
        $disk = config('filesystems.default');
        $baseUrl = config('app.url');

        $defaultImages = [
            'profile' => $baseUrl . '/backend/assets/img/default-profile.png',
            'default' => $baseUrl . '/backend/assets/img/default-image-02.jpg',
            'default_logo' => $baseUrl . '/backend/assets/img/logo.svg',
            'default_favicon' => $baseUrl . '/backend/assets/img/favicon.png',
        ];

        if (!$filePath || !Storage::disk($disk)->exists($filePath)) {
            return [
                'url' => $defaultImages[$default] ?? $defaultImages['default'],
                'extension' => '',
                'size' => '0 B'
            ];
        }

        $fileUrl = Storage::disk($disk)->url($filePath);
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileSize = Storage::disk($disk)->size($filePath);

        if ($disk === 'public' || $disk === 'local') {
            $urlPath = parse_url($fileUrl, PHP_URL_PATH);
            $fileUrl = $baseUrl . '/' . (is_string($urlPath) ? ltrim($urlPath, '/') : '');
        }

        return [
            'url' => $fileUrl,
            'file_name' => $fileName,
            'extension' => $fileExtension,
            'size' => $this->formatFileSize($fileSize)
        ];
    }

    /**
     * Delete file from storage
     *
     * @param string $filePath
     * @param string|null $disk
     * @return bool
     */
    public function deleteFile(string $filePath, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');
        
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }
        
        return false;
    }
}