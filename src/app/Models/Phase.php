<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    /**
     * Validation rules
     */
    public static function rules($phaseId = null)
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Phase::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Phase::class, 'parent_id');
    }
}
