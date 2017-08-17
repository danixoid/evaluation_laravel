<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenceType extends Model
{
    protected $fillable = ['name','note','chief','prof'];

    public function competences()
    {
        return $this->hasMany(\App\Competence::class);
    }
}
