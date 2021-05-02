<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\ExternalServices\PaymentMethods\FirstPaymentMethod;
use App\ExternalServices\PaymentMethods\SecondPaymentMethod;
use App\Http\Controllers\Concerns\Constants\DollarPaymentCharge;
use App\Http\Controllers\Concerns\Constants\ShippingMethods;
use App\Http\Requests\PreparePaymentRequest;
use App\Http\Requests\SendPaymentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function prepare(PreparePaymentRequest $request): View
    {
        $shippingMethod = $request->shipping_method;

        switch ($shippingMethod) {
            case ShippingMethods::PREMIUM:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::PREMIUM];
                break;
            case ShippingMethods::EXPRESS:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::EXPRESS];
                break;
            default:
                $shippingMethodCharge = DollarPaymentCharge::SHIPPING_METHODS[ShippingMethods::BASIC];
        }

        $distanceCharge = $request->distance * DollarPaymentCharge::DISTANCE;
        $weightCharge = $request->weight * DollarPaymentCharge::WEIGHT;
        $totalAmount = $distanceCharge + $weightCharge + $shippingMethodCharge + $request->amount;

        return view(
            'payments.prepare',
            [
                'totalAmount' => $totalAmount,
                'shippingMethod' => $shippingMethod,
            ]
        );
    }

    public function send(SendPaymentRequest $request): RedirectResponse
    {
        $requestData = [
            'amount' => $request->total_amount,
            'description' => $request->description,
            'return_url' => route('payments.show', $request->payment_method_name),
        ];

        if ('first' === $request->payment_method_name) {
            $request->validate(
                [
                    'description' => ['required', 'string', 'min:3', 'max:80'],
                ]
            );
            $paymentMethod = new FirstPaymentMethod();

            return $paymentMethod->paymentRequest($requestData);
        } else {
            $paymentMethod = new SecondPaymentMethod();
            $paymentMethod->processPayment((float)$requestData['amount']);

            return redirect($requestData['return_url']);
        }
    }

    public function show(string $payment): View
    {
        if (!in_array($payment, array_keys(config('payment-methods')))){
            return view('errors.404'); // No pregunten por esto, es chafa.
        }

        return view('payments.show', compact('payment'));
    }
}
