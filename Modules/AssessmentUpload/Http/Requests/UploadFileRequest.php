<?php

namespace Modules\AssessmentUpload\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\AssessmentUpload\Entities\Assessment;

class UploadFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => 'The assessment ID is required.',
            'assessment_id.integer' => 'The assessment ID must be an integer.',
            'files.required' => 'At least one file is required.',
            'files.array' => 'Files must be an array.',
            'files.min' => 'At least one file must be uploaded.',
            'files.max' => 'No more than 10 files can be uploaded.',
            'files.*.required' => 'Each file is required.',
            'files.*.file' => 'Each item must be a valid file.',
            'files.*.mimes' => 'Each file must be a PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'files.*.max' => 'Each file may not be greater than 10MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $assessmentId = $this->route('id'); // TEST after route setup
            $assessment = Assessment::with('files')->find($assessmentId);

            if (!$assessment) {
                $validator->errors()->add('assessment', 'The selected assessment does not exist.');
                return;
            }

            if ($assessment->status === Assessment::STATUS_SUBMITTED) {
                $validator->errors()->add('assessment', 'Files cannot be uploaded to a submitted assessment.');
            }

            $uploadedFiles = $this->file('files', []);

            if (($assessment->files->count() + count($uploadedFiles)) > 10) {
                $validator->errors()->add('files', 'An assessment can have a maximum of 10 files.');
            }

            $existingNames = $assessment->files->pluck('original_name')->map(function ($name) {
                return strtolower($name);
            })->toArray();

            $incomingNames = [];

            foreach ($uploadedFiles as $file) {
                $fileName = $file->getClientOriginalName();
                $fileNameLowercase = strtolower($fileName);

                if (in_array($fileNameLowercase, $existingNames, true)) {
                    $validator->errors()->add('files', "Duplicate file detected: {$fileName}.");
                }

                if (in_array($fileNameLowercase, $incomingNames, true)) {
                    $validator->errors()->add('files', "Duplicate file in upload request: {$fileName}.");
                }

                $incomingNames[] = $fileNameLowercase;
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
