<?php

namespace Modules\AssessmentUpload\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\AssessmentUpload\Http\Requests\UploadFileRequest;
use Modules\AssessmentUpload\Services\AssessmentService;
use Modules\AssessmentUpload\Transformers\AssessmentFileResource;


class AssessmentFileController extends Controller
{
    public function __construct(protected AssessmentService $assessmentService) {}

    public function upload(UploadFileRequest $request, int $id)
    {
        $files = $this->assessmentService->uploadFiles(
            $id,
            $request->file('files')
        );

        return response()->json([
            'message' => 'Files uploaded successfully.',
            'data' => AssessmentFileResource::collection($files),
        ], 201);
    }

    public function destroy(int $id, int $fileId)
    {
        $this->assessmentService->deleteFile($id, $fileId);

        return response()->json([
            'message' => 'File deleted successfully.',
        ]);
    }
}
