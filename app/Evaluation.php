<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{

    protected $fillable = ['org_id','position_id','func_id',
        'eval_type_id','user_id','started_at'];

    protected $appends = ['started'];

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

                foreach ($competences as $competence)
                {
                    $evaluation->competences()->attach($competence);
                }

            }
        );
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

    public function competences()
    {
        return $this
            ->belongsToMany(\App\Competence::class,"eval_competences")
            ->withTimestamps();
    }

    public function getStartedAttribute()
    {
        return $this->started_at != null;
    }
}
