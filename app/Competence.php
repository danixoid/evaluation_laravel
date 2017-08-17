<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competence extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','note','competence_type_id'];

    protected static function boot()
    {
        parent::boot();

        static::created(
            function($competence)
            {
                foreach (\App\EvalLevel::all() as $lvl)
                {
                    $indicator = new \App\Indicator();
                    $indicator->name = "";
                    $indicator->eval_level_id = $lvl->id;
                    $indicator->competence_id = $competence->id;
                    $indicator->save();
                }
            }
        );
    }

    public function indicators() {
        return $this->hasMany(\App\Indicator::class);
    }

    public function competenceType() {
        return $this->belongsTo(\App\CompetenceType::class);
    }

    public function positions()
    {
        return $this
            ->belongsToMany(\App\Competence::class,"competence_positions")
            ->withPivot("func_id", "position_id", "org_id")
            ->withTimestamps();
    }
}
