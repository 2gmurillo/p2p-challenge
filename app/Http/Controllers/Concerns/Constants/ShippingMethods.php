<?php

namespace App\Http\Controllers\Concerns\Constants;

use MyCLabs\Enum\Enum;

class ShippingMethods extends Enum
{
    public const BASIC = 'basic';
    public const EXPRESS = 'express';
    public const PREMIUM = 'premium';
}
