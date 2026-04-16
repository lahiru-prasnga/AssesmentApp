<?php

namespace Modules\AssessmentUpload\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Modules\AssessmentUpload\Entities\AssessmentFile;
use RuntimeException;

class FileStorageService
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function store(UploadedFile $file, string $path): array
    {
        $storedPath = Storage::disk($this->disk)->putFile($path, $file);

        if ($storedPath === false) {
            throw new RuntimeException('Failed to store uploaded file.');
        }

        return [
            'original_name' => $file->getClientOriginalName(),
            'storage_path' => $storedPath,
            'disk' => $this->disk,
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
        ];
    }

    public function delete(AssessmentFile $assessmentFile): bool
    {
       $disk = $assessmentFile->disk;
       $path = $assessmentFile->storage_path;
       
       if (!Storage::disk($disk)->exists($path)) {
            return true;
        }
        
        return Storage::disk($disk)->delete($path);
    }
}