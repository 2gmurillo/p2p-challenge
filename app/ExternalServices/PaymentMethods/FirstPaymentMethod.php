<?php

declare(strict_types=1);

namespace App\ExternalServices\PaymentMethods;

use Illuminate\Http\RedirectResponse;

class FirstPaymentMethod
{
    public function paymentRequest(array $requestData): RedirectResponse
    {
        // Lógica desarrollada con $requestData por el servicio First

        return redirect($requestData['return_url']);
    }

    public function paymentInfo(string $reference): string
    {
        // Lógica desarrollada con $reference por el servicio First

        return 'URL';
    }
}
