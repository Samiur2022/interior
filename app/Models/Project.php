<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'project_name',
        'location',
        'start_date',
        'end_date',
        'status',
    ];
    
    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->hasOne(Budget::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function progressReports()
    {
        return $this->hasMany(ProgressReport::class);
    }

    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'ongoing' => 'info',
            'completed' => 'success',
            'on-hold' => 'danger',
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}