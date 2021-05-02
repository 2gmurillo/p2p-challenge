<?php

namespace Tests\Feature\Payments;

use Tests\TestCase;

class SendTest extends TestCase
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
        $sendPaymentData[$field] = $value;
        $response = $this->post(route('payments.send', $sendPaymentData));
        $response->assertRedirect();
        $response->assertSessionHasErrors($field);
    }

    /**
     * @return array
     */
    public function notValidDataProvider(): array
    {
        return [
            'Test payment_method_name is required' => ['payment_method_name', null],
            'Test payment_method_name is in payment methods' => ['payment_method_name', 'wrong method'],
        ];
    }
}
