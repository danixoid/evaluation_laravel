<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['org_id','name'];

    public function quests()
    {
        return $this
            ->belongsToMany(\App\Quest::class,"position_quest")
            ->withTimestamps();
    }

    public function exams() {
        return $this->hasMany(\App\Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
