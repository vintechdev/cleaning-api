<?php
 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use Uuids;
    protected $table = 'users';
    use SoftDeletes;
    
    protected $fillable = ['id'];
    public $incrementing = false;

}
