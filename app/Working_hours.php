<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Working_hours extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'provider_working_hours';
    use SoftDeletes;
    protected $fillable = ['provider_id', 'working_days', 'start_time', 'end_time'];
}
