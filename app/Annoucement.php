<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Annoucement extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'annoucements';
    use SoftDeletes;
    protected $fillable = ['id'];
}
