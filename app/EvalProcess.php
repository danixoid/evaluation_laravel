<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalProcess extends Model
{
    protected $fillable = ['competence_id','eval_level_id','evaluater_id'];

    public function evaluater()
    {
        return $this->belongsTo(\App\Evaluater::class);
    }

    public function level()
    {
        return $this->belongsTo(\App\EvalLevel::class,'eval_level_id');
    }
}
