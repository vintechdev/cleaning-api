<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentGateway
 * @package App
 */
class PaymentGateway extends Model
{
    const STRIPE_PAYMENT_GATEWAY = 1;

    protected $table = 'payment_gateways';
    protected $fillable = ['id'];
}
