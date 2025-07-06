<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
    use HasFactory;

    protected $table = 'project_contact';

    protected $fillable = [
        'project_id',
        'contact_id',
        'role',
        'hourly_rate',
    ];

    // public $timestamps = true;

    public function projet()
    {
        return $this->belongsTo(Project::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
