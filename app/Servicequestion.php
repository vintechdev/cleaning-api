<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Servicequestion extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'service_questions';
    use SoftDeletes;
    protected $fillable = ['id'];
    // public $incrementing = false;

    public function service()
    {
        return $this->belongsTo(Service::class,'service_id','id');
    }
}
