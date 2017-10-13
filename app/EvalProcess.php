<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalProcess extends Model
{
    protected $fillable = ['indicator_id','eval_level_id','evaluater_id'];

    public function evaluater()
    {
        return $this->belongsTo(\App\Evaluater::class);
    }

    public function level()
    {
        return $this->belongsTo(\App\EvalLevel::class,'eval_level_id');
    }

    public function indicator()
    {
        return $this
            ->belongsTo(\App\Indicator::class,'indicator_id')
            ->withTrashed();
    }

    public function competences()
    {
        return $this
            ->hasManyThrough(\App\Competence::class,\App\Indicator::class,
                'competence_id','id','indicator_id')
            ->withTrashed();
    }
}
