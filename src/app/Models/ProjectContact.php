<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
    //
    public function projet() {
        return $this->belongsTo(Project::class);
    }

    public function contact() {
        return $this->belongsTo(Contact::class);
    }
}
