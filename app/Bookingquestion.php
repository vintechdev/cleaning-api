<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Bookingquestion extends Model
{
	use uuids;
  protected $table = 'booking_questions';
  use SoftDeletes;
  protected $fillable = ['id'];
  public $incrementing = false;

  public function service()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }

}
