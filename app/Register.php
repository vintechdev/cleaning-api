<?php
 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    protected $table = 'users';
    use SoftDeletes;
    
    protected $fillable = ['id'];
    public $incrementing = false;

}
