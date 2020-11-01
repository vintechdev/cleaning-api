<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
class Providermetadatum extends Model
{
  use uuids;
  protected $table = 'providermetadata';
  use SoftDeletes;
  protected $fillable = ['id'];
  public $incrementing = false;
}
