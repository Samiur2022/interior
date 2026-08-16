<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'estimated_cost',
        'actual_cost',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Accessor for variance
    public function getVarianceAttribute()
    {
        return $this->estimated_cost - ($this->actual_cost ?? 0);
    }

    public function getVarianceStatusAttribute()
    {
        $variance = $this->variance;
        if ($variance > 0) return 'Under Budget';
        if ($variance < 0) return 'Over Budget';
        return 'On Budget';
    }
}