<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quest extends Model
{
    use SoftDeletes;

    protected $fillable = ['author_id','task','timer'];

    protected $appends = ['shortTask'];

    public function author()
    {
        return $this->belongsTo("App\User");
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function($quest)
            {
                $quest->positions()->detach();
            }
        );
    }

    public function tickets()
    {
        return $this->hasMany(\App\Ticket::class);
    }


    public function self()
    {
        return $this
            ->belongsToMany(\App\Quest::class,"position_quest")
            ->withPivot("func_id", "position_id", "org_id")
            ->withTimestamps();
    }

    public function orgs()
    {
        return $this
            ->belongsToMany(\App\Org::class,"position_quest")
            ->withPivot("func_id", "position_id")
            ->withTimestamps();
    }

    public function funcs()
    {
        return $this
            ->belongsToMany(\App\Func::class,"position_quest")
            ->withPivot("org_id", "position_id")
            ->withTimestamps();
    }

    public function positions()
    {
        return $this
            ->belongsToMany(\App\Position::class,"position_quest")
            ->withPivot("org_id", "func_id")
            ->withTimestamps();
    }

    public function getShortTaskAttribute() {
        $str = mb_ereg_replace("&\w+\;","",$this->task);
        $str = mb_substr(mb_ereg_replace("<[^>]+>","",$str),0,50);
        return $str;
    }

    public function hasPosition($id)
    {
        if ($this->position()->find($id)) {
            return true;
        }

        return false;
    }


    public function scopeGetQuestForExam($query,$data) {
        return $query
            ->whereHas('orgs',function($q) use ($data) {
                return $q->whereOrgId($data['org_id']);
            })
            ->whereHas('positions',function($q) use ($data) {
                return $q->wherePositionId($data['position_id']);
            })
            ->where(function($q) use ($data)
            {
                if(isset($data['func_id']))
                {
                    return $q->whereHas('funcs',function($q) use ($data) {
                        return $q->whereFuncId($data['func_id']);
                    });
                }
                return $q;
            })
            ->inRandomOrder();
    }
}
