<?php

namespace Modules\AssessmentUpload\Services;

use Modules\AssessmentUpload\Repositories\Contracts\AssessmentRepositoryInterface;
use Modules\AssessmentUpload\Entities\Assessment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RuntimeException;


class AssessmentService
{
    public function __construct(
        protected AssessmentRepositoryInterface $assessmentRepository,
        protected FileStorageService $fileStorageService
    ) {}

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->assessmentRepository->paginate($perPage);
    }

    public function submit(int $id): Assessment
    {
        $assessment = $this->assessmentRepository->findWithFiles($id);

        if (! $assessment) {
            throw new RuntimeException('Assessment not found.');
        }

        if ($assessment->status === Assessment::STATUS_SUBMITTED) {
            throw new RuntimeException('Assessment is already submitted.');
        }

        if ($assessment->files()->count() === 0) {
            throw new RuntimeException('At least one file is required before submission.');
        }

        $assessment->status = Assessment::STATUS_SUBMITTED;
        $assessment->submitted_at = now();

        $this->assessmentRepository->save($assessment);

        return $assessment;
    }

    public function create(array $data): Assessment
    {
        $assessment = new Assessment();
        $assessment->title = $data['title'];
        $assessment->description = $data['description'] ?? null;
        $assessment->subject = $data['subject'] ?? null;
        $assessment->created_by = $data['created_by'];
        // $assessment->created_by = Auth::id(); // TRY THIS AFTER AUTH SETUP
        $assessment->status = Assessment::STATUS_DRAFT;

        $this->assessmentRepository->save($assessment);
        return $assessment;
    }

     public function findWithFiles(int $id): ?Assessment
    {
        return $this->assessmentRepository->findWithFiles($id);
    }

    public function findById(int $id): ?Assessment
    {
        return $this->assessmentRepository->findById($id);
    }

    public function uploadFiles(int $assessmentId, array $files): array
    {
        $assessment = $this->assessmentRepository->findWithFiles($assessmentId);

    if (! $assessment) {
        throw new RuntimeException('Assessment not found.');
    }

    if ($assessment->status === Assessment::STATUS_SUBMITTED) {
        throw new RuntimeException('Cannot upload files to a submitted assessment.');
    }

    $savedFiles = [];

    foreach ($files as $file) {
        $storedFile = $this->fileStorageService->store($file, 'assessments');

        $savedFiles[] = $assessment->files()->create([
            'original_name' => $storedFile['original_name'],
            'storage_path' => $storedFile['storage_path'],
            'disk' => $storedFile['disk'],
            'mime_type' => $storedFile['mime_type'],
            'size_bytes' => $storedFile['size_bytes'],
        ]);
    }

        return $savedFiles;
    }

    public function deleteFile(int $assessmentId, int $fileId): void
    {
        $assessment = $this->assessmentRepository->findWithFiles($assessmentId);

        if (! $assessment) {
            throw new RuntimeException('Assessment not found.');
        }

        if ($assessment->status === Assessment::STATUS_SUBMITTED) {
            throw new RuntimeException('Cannot delete files from a submitted assessment.');
        }

        $file = $assessment->files()->where('id', $fileId)->first();

        if (! $file) {
            throw new RuntimeException('File not found for this assessment.');
        }
        
        $this->fileStorageService->delete($file);

        $file->delete();
    }

}