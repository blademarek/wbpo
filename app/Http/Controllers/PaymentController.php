<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\DefaultPaymentService;

class PaymentController extends Controller
{
    protected DefaultPaymentService $defaultPaymentService;

    public function __construct(DefaultPaymentService $defaultPaymentService)
    {
        $this->defaultPaymentService = $defaultPaymentService;
    }

    public function createPaymentLink(Request $request): JsonResponse
    {
        $paymentLink = $this->defaultPaymentService->createPaymentLink($request->all());

        return response()->json(['payment_link' => $paymentLink]);
    }
}
