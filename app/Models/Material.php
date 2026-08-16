<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_name',
        'unit',
        'unit_price',
    ];

     protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    // Relationships
    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }
}