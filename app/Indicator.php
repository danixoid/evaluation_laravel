<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    protected $fillable = ['name','competence_id','eval_level_id'];

    public function competence()
    {
        return $this->belongsTo(\App\Competence::class);
    }

    public function evalLevel()
    {
        return $this->belongsTo(\App\EvalLevel::class);
    }
}
