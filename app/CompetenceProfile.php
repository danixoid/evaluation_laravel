<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenceProfile extends Model
{
    protected $fillable = ['org_id','func_id','position_id','competence_id','eval_level_id'];


    public function org()
    {
        return $this->belongsTo(\App\Org::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Position::class);
    }

    public function func()
    {
        return $this->belongsTo(\App\Func::class);
    }

    public function competence()
    {
        return $this->belongsTo(\App\Competence::class);
    }

    public function level()
    {
        return $this->belongsTo(\App\EvalLevel::class,'eval_level_id');
    }

}
