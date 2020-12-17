<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocodes extends Model
{
    //
    protected $table = 'promocodes';
    use SoftDeletes;
    protected $fillable = ['id'];
}
