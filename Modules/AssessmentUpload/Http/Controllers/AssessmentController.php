<?php

namespace Modules\AssessmentUpload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AssessmentUpload\Http\Requests\StoreAssessmentRequest;
use Modules\AssessmentUpload\Http\Requests\SubmitAssessmentRequest;
use Modules\AssessmentUpload\Services\AssessmentService;
use Modules\AssessmentUpload\Transformers\AssessmentResource;

class AssessmentController extends Controller
{
    public function __construct(protected AssessmentService $assessmentService) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $assessments = $this->assessmentService->paginate($perPage);

        return AssessmentResource::collection($assessments);
    }

    public function store(StoreAssessmentRequest $request)
    {
        $assessment = $this->assessmentService->create($request->validated());

        return (new AssessmentResource($assessment))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id)
    {
        $assessment = $this->assessmentService->findWithFiles($id);

        if (! $assessment) {
            return response()->json([
                'message' => 'Assessment not found.',
            ], 404);
        }

        return new AssessmentResource($assessment);
    }

     public function submit(SubmitAssessmentRequest $request, int $id)
    {
        $assessment = $this->assessmentService->submit($id);

        return response()->json([
            'message' => 'Assessment submitted successfully.',
            'data' => new AssessmentResource($assessment),
        ]);
    }
}
