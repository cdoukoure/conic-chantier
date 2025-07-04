<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'address',
        'siret',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Types de contacts disponibles
     */
    public const TYPES = [
        'client',
        'fournisseur',
        'prestataire',
        'ouvrier',
        'autre'
    ];

    /**
     * Validation rules
     */
    public static function rules($contactId = null)
    {
        return [
            'type' => 'required|in:' . implode(',', self::TYPES),
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $contactId,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'siret' => 'nullable|string|max:14',
            'metadata' => 'nullable|array'
        ];
    }

    public function projectContacts()
    {
        return $this->hasMany(ProjectContact::class, 'contact_id');
    }

    /*
    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('role', 'hourly_rate')
            ->using(ProjectContact::class);
    }
    //*/

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_contact')
            ->withPivot(['role', 'hourly_rate'])
            ->withTimestamps(); // si tu as des timestamps
    }

    public function financialMovements()
    {
        return $this->hasMany(FinancialMovement::class);
    }
}
