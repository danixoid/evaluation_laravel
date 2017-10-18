<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competence extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','note','competence_type_id'];

    public function indicators() {
        return $this->hasMany(\App\Indicator::class);
    }

    public function profiles() {
        return $this->hasMany(\App\CompetenceProfile::class,
            'competence_id');
    }

    public function type() {
        return $this->belongsTo(\App\CompetenceType::class,'competence_type_id');
    }

    public function positions()
    {
        return $this
            ->belongsToMany(\App\Competence::class,"competence_positions")
            ->withPivot("func_id", "position_id", "org_id","eval_level_id")
            ->withTimestamps();
    }

}
