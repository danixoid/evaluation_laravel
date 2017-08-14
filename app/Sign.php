<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
    protected $fillable = ['signer_id','exam_id','xml'];

    public function signer() {
        return $this->belongsTo(\App\User::class);
    }

    public function exam() {
        return $this->belongsTo(\App\Exam::class);
    }
}
