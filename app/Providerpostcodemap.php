<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Postcode;

class Providerpostcodemap extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'provider_postcode_maps';
    use SoftDeletes;
    protected $fillable = ['id'];

    public function postcode()
    {
        return $this->belongsTo(Postcode::class,'postcode_id','id');
    }
}
