<?php

namespace Tests\Unit;

use App\Services\DefaultPaymentService;
use Tests\BaseWbpoTest;

class PaymentLinkTest extends BaseWbpoTest
{
    public function testCreatePaymentLink()
    {
        $paymentService = new DefaultPaymentService();

        $paymentLink = $paymentService->createPaymentLink();

        $this->assertIsString($paymentLink);
        $this->assertNotEmpty($paymentLink);
    }
}
