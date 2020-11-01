<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postcode extends Model
{
  protected $table = 'postcodes';
  use SoftDeletes;
  protected $fillable = ['id'];
}
