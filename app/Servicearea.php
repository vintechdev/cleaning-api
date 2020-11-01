<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Servicearea extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'provider_postcode_maps';
    use SoftDeletes;
    protected $fillable = ['id'];
    // public $incrementing = false;
}
