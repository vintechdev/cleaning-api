<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Providerportfolios extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'providerportfolios';
    use SoftDeletes;
    protected $fillable = ['id'];
}
