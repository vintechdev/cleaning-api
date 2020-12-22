<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_CUSTOMER = 'customer';
    const ROLE_PROVIDER = 'provider';
    const ROLE_ADMIN = 'admin';
    const ROLE_PUBLIC = 'public';

    protected $table = 'roles';
    
    public function users()
    {
        return $this
            ->belongsToMany('App\User')
            ->withTimestamps();
    }
}
