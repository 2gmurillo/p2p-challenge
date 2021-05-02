<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\ExternalServices\PaymentMethods\FirstPaymentMethod;
use App\ExternalServices\PaymentMethods\SecondPaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Request $request): View
    {
        $request->validate(
            [
                'amount' => ['required', 'numeric', 'min:1'],
                'distance' => ['required', 'numeric', 'min:1'],
                'weight' => ['required', 'numeric', 'min:1'],
            ]
        );

        switch ($request->shipping_method) {
            case 'premium':
                $shippingMethodCharge = 5;
                break;
            case 'express':
                $shippingMethodCharge = 3;
                break;
            default:
                $shippingMethodCharge = 1;
        }

        $distanceCharge = $request->distance * 0.25;
        $weightCharge = $request->weight * 0.5;
        $totalAmount = $distanceCharge + $weightCharge + $shippingMethodCharge + $request->amount;

        return view('payments.create', compact('totalAmount'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'payment_method_name' => ['required', Rule::in(array_column(config('payment-methods'), 'name'))],
            ]
        );

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
        return view('payments.show', compact('payment'));
    }
}
