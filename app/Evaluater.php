<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluater extends Model
{
    protected $fillable = ['evaluation_id','user_id','eval_role_id'];

    protected $appends = ['finished'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(\App\Evaluation::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\EvalRole::class,'eval_role_id');
    }

    public function processes()
    {
        return $this->hasMany(\App\EvalProcess::class);
    }

    public function indicators()
    {
        return $this
            ->belongsToMany(\App\Indicator::class,\App\EvalProcess::class)
            ->withTimestamps();
    }

    public function getFinishedAttribute()
    {
        return $this->evaluation->started
            && $this->processes()->whereNull('eval_level_id')->count() == 0;
    }
}
