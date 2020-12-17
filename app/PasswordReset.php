<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    public $timestamps = false;
    protected $primaryKey = 'email';
    protected $fillable = ['email','token'];
    
    //
}
