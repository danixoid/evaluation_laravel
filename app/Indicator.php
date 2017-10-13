<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','competence_id'];

    public function competence()
    {
        return $this->belongsTo(\App\Competence::class);
    }

    public function processes() {
        return $this->hasMany(\App\EvalProcess::class);
    }
}
