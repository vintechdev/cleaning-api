<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
class Plan extends Model
{
	use uuids;
  protected $table = 'plans';
  use SoftDeletes;
  protected $fillable = ['id'];
  public $incrementing = false;
}
