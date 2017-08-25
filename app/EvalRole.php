<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalRole extends Model
{
    protected $fillable = ['name','title','code'];

    public function types()
    {
        return $this
            ->belongsToMany(\App\EvalType::class,"type_roles",
                'eval_role_id','eval_type_id')
            ->withPivot("min","max")
            ->withTimestamps();
    }
}
