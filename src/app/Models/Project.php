<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

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

    /*
    public function contacts()
    {
        return $this->belongsToMany(Contact::class)
            ->withPivot('role', 'hourly_rate')
            ->using(ProjectContact::class);
    }
    //*/

    public function projectContacts()
    {
        return $this->hasMany(ProjectContact::class, 'project_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'project_contact')
            ->withPivot(['role', 'hourly_rate'])
            ->withTimestamps();
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
    public function prestataires()
    {
        return $this->contacts()
            ->wherePivot('role', 'prestataire');
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


    /*
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
    //*/
}
