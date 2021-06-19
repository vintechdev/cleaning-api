<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserBadge extends Model
{
    //
    protected $table = 'user_badges';
    use SoftDeletes;
    protected $fillable = ['id'];

    public function badge()
    {
        return $this->belongsTo(Badges::class);
    }
}
