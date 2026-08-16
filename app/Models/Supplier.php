<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'phone',
        'email',
        'address',
    ];

    // Relationships
    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }
}