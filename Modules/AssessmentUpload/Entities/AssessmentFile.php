<?php

namespace Modules\AssessmentUpload\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentFile extends Model
{
    use HasFactory;

    public const STATUS_UPLOADED = 'uploaded';
    public const STATUS_PENDING_REMOVAL = 'pending_removal';
    public const STATUS_REMOVED = 'removed';

    protected $fillable = [
        'assessment_id',
        'original_name',
        'storage_path',
        'disk',
        'mime_type',
        'size_bytes',
        'status',
    ];
    
    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    protected static function newFactory()
    {
        return \Modules\AssessmentUpload\Database\factories\AssessmentFileFactory::new();
    }
}
