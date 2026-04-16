<?php

namespace Modules\AssessmentUpload\Services;

use Modules\AssessmentUpload\Repositories\Contracts\AssessmentRepositoryInterface;
use Modules\AssessmentUpload\Entities\Assessment;

class AssessmentService
{
    protected $assessmentRepository;

    public function __construct(AssessmentRepositoryInterface $assessmentRepository)
    {
        $this->assessmentRepository = $assessmentRepository;
    }

   // To Do: implement paginate function  paginate(array $filters) — returns a paginated collection


    public function submit(Assessment $assessment)
    {
        if ($assessment->files()->count() === 0) {
            throw new RuntimeException('At least one file is required before submission.');
        }
        
        $assessment->status = Assessment::STATUS_SUBMITTED; 
        $assessment->submitted_at = now();
        return $this->assessmentRepository->save($assessment);
    }

    public function create(array $data): Assessment
    {
        $assessment = new Assessment();
        $assessment->title = $data['title'];
        $assessment->description = $data['description'] ?? null;
        $assessment->subject = $data['subject'] ?? null;
        $assessment->created_by = $data['created_by'];
        $assessment->status = Assessment::STATUS_DRAFT;

        $this->assessmentRepository->save($assessment);
        return $assessment;
    }
    
}