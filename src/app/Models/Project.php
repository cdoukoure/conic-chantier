<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'type',
        'description',
        'budget',
        'start_date',
        'end_date',
        'parent_id',
        'client_id',
        'phase_id',
        'custom_fields',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'custom_fields' => 'array',
    ];

    /**
     * Types de contacts disponibles
     */
    public const TYPES = ['projet', 'chantier'];

    /**
     * Validation rules
     */
    public static function rules($projectId = null)
    {
        return [
            'type' => 'required|in:' . implode(',', self::TYPES),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:2',
            // 'email' => 'nullable|email|max:255|unique:contacts,email,' . $contactId,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'client_id' => 'nullable',
            'parent_id' => 'nullable',
            'phase_id' => 'nullable',
            'custom_fields' => 'nullable|array'
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    public function client()
    {
        return $this->belongsTo(Contact::class, 'client_id');
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class, 'phase_id');
    }

    public function chantiers()
    {
        return $this->hasMany(Project::class, 'parent_id')->where('type', 'chantier');
    }

    public function projectContacts()
    {
        return $this->hasMany(ProjectContact::class, 'project_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'project_contact')
            ->withPivot(['role', 'hourly_rate'])
            ->withTimestamps(); // si tu as des timestamps
        //->withoutTimestamps();
    }

    public function workers()
    {
        return $this->contacts()
            ->wherePivot('role', 'ouvrier');
    }
    public function suppliers()
    {
        return $this->contacts()
            ->wherePivot('role', 'fournisseur');
    }
    public function providers()
    {
        return $this->contacts()
            ->wherePivot('role', 'prestataire');
    }
    public function othersContacts()
    {
        return $this->contacts()
            ->wherePivot('role', 'autre');
    }


    public function financialMovements()
    {
        return $this->hasMany(FinancialMovement::class);
    }

    public function totalFinancialMovementsIn()
    {
        return $this->financialMovements()
            ->where('flow_type', 'in')
            ->sum('amount');
    }
    public function totalFinancialMovementsOut()
    {
        return $this->financialMovements()
            ->where('flow_type', 'out')
            ->sum('amount');
    }

    public function financialMovementsOutChildren()
    {
        $projetIds = $this->children()->pluck('id')->push($this->id);

        return FinancialMovement::whereIn('projet_id', $projetIds)
            ->where('flow_type', 'out')
            ->get();
    }

    public function financialMovementsOutByCategory()
    {
        return $this->financialMovements()
            ->with('categorie')
            ->get()
            ->groupBy('categorie.name');
    }
}
