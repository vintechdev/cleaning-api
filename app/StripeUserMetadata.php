<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripeUserMetadata
 * @package App
 */
class StripeUserMetadata extends Model
{
    protected $table = 'stripe_user_metadata';
    protected $fillable = ['id'];
    public $timestamps = false;
}