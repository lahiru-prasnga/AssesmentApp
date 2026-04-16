<?php

namespace Modules\AssessmentUpload\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\AssessmentUpload\Entities\Assessment;

class SubmitAssessmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $assessmentId = $this->route('id'); // Test after route setup
            $assessment = Assessment::with('files')->find($assessmentId);

            if (!$assessment) {
                $validator->errors()->add('assessment', 'The selected assessment does not exist.');
                return;
            }

            if ($assessment->status === Assessment::STATUS_SUBMITTED) {
                $validator->errors()->add('assessment', 'This assessment has already been submitted.');
            }

            if ($assessment->files->count() < 1) {
                $validator->errors()->add('files', 'At least one file must be uploaded before submission.');
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
