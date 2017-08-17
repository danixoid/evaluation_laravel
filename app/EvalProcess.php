<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalProcess extends Model
{
    protected $fillable = ['competence_id','eval_level_id','eval_user_id'];
}
