<?php

namespace Tests\Feature\Purchases;

use Tests\TestCase;

class PayTest extends TestCase
{
    /**
     * @test
     * @dataProvider notValidDataProvider
     *
     * @param string $field
     * @param mixed|null $value
     */
    public function itCannotSendAPaymentWhenDataIsNotValid(string $field, $value = null)
    {
        $paymentData[$field] = $value;
        $response = $this->post(route('purchases.pay', $paymentData));
        $response->assertRedirect();
        $response->assertSessionHasErrors($field);
    }

    /**
     * @return array
     */
    public function notValidDataProvider(): array
    {
        return [
            'Test payment_method is required' => ['payment_method', null],
            'Test payment_method is in payment methods' => ['payment_method', 'wrong method'],
        ];
    }
}
