<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseWbpoTest;

class AuthApiTest extends BaseWbpoTest
{
    public function testPassportGuardApi_Success()
    {
        $this->createUser();

        Passport::actingAs(
            $this->user
        );

        $response = $this->post('/api/test');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testPassportGuardApi_Error()
    {
        $response = $this->post('/api/test');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
