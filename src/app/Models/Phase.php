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
        'order',
    ];

    /**
     * Validation rules
     */
    public static function rules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:phases,name,' . ($id ?? 'NULL') . ',id',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:phases,id|not_in:' . ($id ?? 'NULL'),
            'order' => 'nullable|numeric',
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

    public function projects()
    {
        return $this->hasMany(Project::class, 'phase_id');
    }
}
