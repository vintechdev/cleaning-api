<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Bookingaddress extends Model
{
  protected $table = 'booking_addresses';
  use SoftDeletes;
  protected $fillable = ['id'];
  public $incrementing = false;
    /**
     * bookingaddress hasOne booking
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     * */
    public function booking()
    {
        return $this->hasOne(\App\Booking::class);
    }
 }