<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'project_id',
        'category_id',
        'contact_id',
        'flow_type',
        'amount',
        'operation_date',
        'reference',
        'payment_method',
        'description',
        'document_path',
    ];
    
    public function projet() {
        return $this->belongsTo(Project::class);
    }

    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    public function category() {
        return $this->belongsTo(FinancialMovementCategorie::class);
    }
}
