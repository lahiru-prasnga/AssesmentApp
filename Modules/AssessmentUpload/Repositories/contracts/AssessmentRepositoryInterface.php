<?php
namespace Modules\AssessmentUpload\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\AssessmentUpload\Entities\Assessment;

interface AssessmentRepositoryInterface
{
    public function findById(int $id): ?Assessment;
    public function findWithFiles(int $id): ?Assessment;
    public function paginate(int $perPage): LengthAwarePaginator;
    public function save(Assessment $assessment): bool;
}
