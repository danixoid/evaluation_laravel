<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalLevel extends Model
{
    protected $fillable = ['name','note','level','min','max'];
}
