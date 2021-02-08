<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
class Badges extends Model
{
    protected $table = 'badges';
    use SoftDeletes;
    protected $fillable = ['id'];
   
}
