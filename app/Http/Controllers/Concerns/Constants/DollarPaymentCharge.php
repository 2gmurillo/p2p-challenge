<?php

namespace App\Http\Controllers\Concerns\Constants;

class DollarPaymentCharge
{
    public const SHIPPING_METHODS = [
        ShippingMethods::BASIC => 1,
        ShippingMethods::EXPRESS => 3,
        ShippingMethods::PREMIUM => 5,
    ];
    public const DISTANCE = 0.25;
    public const WEIGHT = 0.5;
}
