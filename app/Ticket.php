<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Ticket extends Model
{
    protected $fillable = ['quest_id','exam_id','answer','mark','note','started_at','finished_at'];

    protected static function boot()
    {
        parent::boot();

        static::updated(
            function ($ticket) {
                //ты тестируемый
                //и экзамен закончен
                if ($ticket->exam->isUser && $ticket->exam->finished) {
                    Mail::queue(new \App\Mail\ChiefMail($ticket));
                }
            }
        );
    }

    public function exam() {
        return $this->belongsTo(\App\Exam::class);
    }

    public function quest() {
        return $this
            ->belongsTo(\App\Quest::class)
            ->withTrashed();
    }
}
