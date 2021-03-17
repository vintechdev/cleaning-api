<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Announcement extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'announcements';
    use SoftDeletes;
    protected $fillable = ['id'];
}
