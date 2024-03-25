<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseWbpoTest;

class PaymentLinkTest extends BaseWbpoTest
{
    public function testCreatePaymentLink()
    {
        $response = $this->json('POST', '/api/payment/link');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['payment_link'])
            ->assertJson(['payment_link' => 'https://example.com/payment-link']);
    }
}
