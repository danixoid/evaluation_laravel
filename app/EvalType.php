<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvalType extends Model
{
    protected $fillable = ['name','note'];

    public function roles()
    {
        return $this
            ->belongsToMany(\App\EvalRole::class,"type_roles",
                'eval_type_id','eval_role_id')
            ->withPivot("max")
            ->withTimestamps();
    }
}
