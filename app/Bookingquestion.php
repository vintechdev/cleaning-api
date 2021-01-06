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

  public function service_questions()
    {
        return $this->belongsTo(Servicequestion::class,'service_question_id','id');
    }

}
