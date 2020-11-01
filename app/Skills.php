<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Skills extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'providerskill';
    use SoftDeletes;
    protected $fillable = ['id'];
}
