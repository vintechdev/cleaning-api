<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Userreview extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'user_reviews';
    use SoftDeletes;
    protected $fillable = ['id'];

    public function reviewby()
    {
        return $this->belongsTo(User::class,'user_review_by','id');
    }
    public function reviewfor()
    {
        return $this->belongsTo(User::class,'user_review_for','id');
    }
}
