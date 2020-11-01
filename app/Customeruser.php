<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Customeruser extends Model
{
    use uuids;
	use HasApiTokens, Notifiable;
    protected $table = 'users';
    use SoftDeletes;
    protected $fillable = ['id'];
    public $incrementing = false;
}
