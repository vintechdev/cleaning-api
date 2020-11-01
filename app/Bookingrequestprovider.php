<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Bookingrequestprovider extends Model
{
	use uuids;
  protected $table = 'booking_request_providers';
  use SoftDeletes;
  protected $fillable = ['id'];
  public $incrementing = false;
}
