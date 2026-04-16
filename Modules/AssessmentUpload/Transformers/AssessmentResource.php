<?php

namespace Modules\AssessmentUpload\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'files_count' => $this->files_count
                ?? ($this->relationLoaded('files') ? $this->files->count() : 0),
            'files' => $this->when(
                $this->relationLoaded('files'),
                fn () => AssessmentFileResource::collection($this->files)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
