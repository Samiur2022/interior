<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'work_type',
        'progress_percent',
        'description',
        'image',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}