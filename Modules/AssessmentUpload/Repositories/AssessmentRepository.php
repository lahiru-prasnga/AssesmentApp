<?php

namespace Modules\AssessmentUpload\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\AssessmentUpload\Entities\Assessment;
use Modules\AssessmentUpload\Repositories\Contracts\AssessmentRepositoryInterface;

class AssessmentRepository implements AssessmentRepositoryInterface
{
    public function findById(int $id): ?Assessment
    {
        return Assessment::find($id);
    }

    public function findWithFiles(int $id): ?Assessment
    {
        return Assessment::with('files')->find($id);
    }

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return Assessment::paginate($perPage);
    }

    public function save(Assessment $assessment): bool
    {
        return $assessment->save();
    }
}