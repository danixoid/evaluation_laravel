<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{

    protected $fillable = ['org_id','position_id','func_id',
        'eval_type_id','user_id','started_at','finished_at'];

    protected $appends = ['started','finished','is_total','enough'];

    protected $dates = ['started_at','finished_at'];

    protected static function boot()
    {
        parent::boot();

        static::created(
            function($evaluation)
            {

                // CREATE SELF EVALUATION
                $role = $evaluation->type->roles()->whereCode('self')->first();

                if ($role)
                {
                    $evaluater = \App\Evaluater::create([
                        'evaluation_id' => $evaluation->id,
                        'user_id' => $evaluation->evaluated->id,
                        'eval_role_id' => $role->id,
                    ]);
                }

            }
        );
/*
        static::updating(
            function($evaluation)
            {

                $old = \App\Evaluation::find($evaluation->id);

                if($evaluation->started && !$old->started) {
                    // CHOOSE COMPETENCE FOR EVALUATION
                    $competences = \App\Competence::whereDoesntHave('positions')
                        ->orWhereHas('positions',function($q) use ($evaluation)
                        {
                            return $q
                                ->where('position_id',$evaluation->position_id)
                                ->where('org_id',$evaluation->org_id)
                                ->where(function($q) use ($evaluation) {
                                    return $q
                                        ->where('func_id',$evaluation->func_id)
                                        ->orWhereNull('func_id');
                                });
                        })
                        ->get();

                    foreach ($evaluation->evaluaters as $evaluater)
                    {
                        foreach ($competences as $competence)
                        {
                            $process = \App\EvalProcess::create([
                                'evaluater_id' => $evaluater->id,
                                'competence_id' => $competence->id,
                            ]);
                        }
                    }

                }
            }
        );*/
    }

    public function evaluated()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }

    public function evaluaters()
    {
        return $this->hasMany(\App\Evaluater::class);
    }

    public function type()
    {
        return $this->belongsTo(\App\EvalType::class,'eval_type_id');
    }

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

    public function getStartedAttribute()
    {
        return $this->started_at != null;
    }

    public function getFinishedAttribute()
    {
        if($this->finished_at < \Carbon\Carbon::now()) return true;

        return $this
                ->evaluaters()
                ->whereHas('processes',function($q) {
                    return $q->whereNull('eval_level_id');
                })
                ->count() == 0;
    }

    public function getEnoughAttribute()
    {
        foreach($this->type->roles as $role)
        {
            $count = $this->evaluaters()->whereEvalRoleId($role->id)->count();
            if($count < $role->pivot->min
                || $count > $role->pivot->max)
                return false;
        }

        return true;
    }
}
