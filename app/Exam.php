<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Exam extends Model
{

    protected $fillable = ['org_id','func_id','position_id','user_id','chief_id','count'];

    protected $hidden = [
        'signs', 'started','finished','color','amISigner',
        'startedDate','chiefHasNoteMark'
    ];

    protected $appends = [
        'isUser','isChief','started','finished','color','amISigner',
        'startedDate','finishedDate','chiefHasNoteMark'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function($exam)
            {
                $exam->tickets()->delete();
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id");
    }

    public function chief()
    {
        return $this->belongsTo(\App\User::class,"chief_id");
    }

    public function signs()
    {
        return $this->hasMany(\App\Sign::class);
    }

    public function org()
    {
        return $this->belongsTo(\App\Org::class);
    }

    public function func()
    {
        return $this->belongsTo(\App\Func::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Position::class);
    }

    public function tickets()
    {
        return $this->hasMany(\App\Ticket::class);
    }

    public function quests()
    {
        return $this->hasManyThrough(\App\Quest::class,
            \App\Ticket::class,"exam_id",
            "id","id")
            ->withTrashed();
    }

    public function getIsUserAttribute()
    {
        return Auth::check() && Auth::user()->id == $this->user_id;
    }

    public function getIsChiefAttribute()
    {
        return Auth::check() && Auth::user()->id == $this->chief_id;
    }

    public function getStartedAttribute()
    {
        $finish = false;
        foreach ($this->tickets as $ticket)
        {
            if($ticket->started_at ) {
                $finish = true;
            }
        }

        return $finish;
    }

    public function getFinishedAttribute()
    {
        $finish = true;
        foreach ($this->tickets as $ticket)
        {
            if(!$ticket->finished_at ) {
                $finish = false;
            }
        }

        return $finish;
    }

    public function getStartedDateAttribute()
    {
        $date = \Carbon\Carbon::now();
        foreach ($this->tickets as $ticket)
        {
            if(strtotime($ticket->started_at) < strtotime($date) ) {
                $date = $ticket->started_at;
            }
        }

        return $date;
    }

    public function getFinishedDateAttribute()
    {
        $date = \Carbon\Carbon::create(2000);
        foreach ($this->tickets as $ticket)
        {
            if(strtotime($ticket->finished_at) > strtotime($date) ) {
                $date = $ticket->finished_at;
            }
        }

        return $date;
    }

    public function getStatusAttribute()
    {
        return $this->finished
            ? "exam_finished"
            : ( $this->started
                ? "exam_started"
                : "exam_not_started");
    }

    public function getColorAttribute()
    {
        return $this->finished
            ? "success"
            : ( $this->started
                ? "primary"
                : "warning");
    }

    public function getAmISignerAttribute()
    {
        return Auth::check() &&
            $this
                ->signs()
                ->where('signer_id',Auth::user()->id)
                ->count() > 0;
    }

    public function getChiefHasNoteMarkAttribute()
    {
        return $this->isChief &&
            $this->tickets()
                    ->whereNull('note')
                    ->orWhereNull('mark')
                    ->count() > 0;
    }
}
