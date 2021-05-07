<?php

declare(strict_types=1);

namespace App\ExternalServices\PaymentMethods;

use Illuminate\Http\RedirectResponse;

class FirstPaymentMethod
{
    public function paymentRequest(float $amount, string $description, string $returnUrl): RedirectResponse
    {
        // Lógica desarrollada con $amount, $description y $returnUrl por el servicio First

        return redirect($returnUrl);
    }

    public function paymentInfo(string $reference): string
    {
        // Lógica desarrollada con $reference por el servicio First

        return 'redirectUrl';
    }
}
