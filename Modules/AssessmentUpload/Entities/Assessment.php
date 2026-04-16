<?php

namespace Modules\AssessmentUpload\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWED = 'reviewed';
    
    protected $fillable = [
        'title',
        'description',
        'subject',
        'status',
        'submitted_at',
        'created_by',
    ];
    
    public function files()
    {
        return $this->hasMany(AssessmentFile::class, 'assessment_id');
    }
    

    protected static function newFactory()
    {
        return \Modules\AssessmentUpload\Database\factories\AssessmentFactory::new();
    }

    
}
