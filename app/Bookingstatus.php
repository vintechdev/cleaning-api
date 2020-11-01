<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookingstatus extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'booking_status';
    use SoftDeletes;
    protected $fillable = ['id'];
}
