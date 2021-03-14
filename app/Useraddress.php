<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Useraddress extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'user_addresses';
    use SoftDeletes;
    protected $fillable = ['id'];
}
