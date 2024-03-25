<?php

namespace Tests\Feature;

use App\Events\TransactionCreated;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseWbpoTest;

class TransactionTest extends BaseWbpoTest
{
    protected TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = new TransactionRepository();
    }

    public function testCreateTransaction_Success()
    {
        Event::fake();

        $this->createUser();

        $response = $this->json('POST', '/api/transactions', $this->getTransactionDetails());

        $response->assertStatus(Response::HTTP_CREATED);

        Event::assertDispatched(TransactionCreated::class);
    }

    public function testCreateTransaction_ValidationError()
    {
        $response = $this->json('POST', '/api/transactions', []);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testGetTransactionById_Success()
    {
        $this->createUser()->createTransaction();

        $response = $this->json('GET', '/api/transactions/' . $this->transaction->id);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson($this->transaction->toArray());
    }

    public function testGetTransactionById_Error()
    {
        $response = $this->json('GET', '/api/transactions/9999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateTransaction_Success()
    {
        $this->createUser()->createTransaction();

        $updatedData = [
            'amount' => 200,
            'currency' => 'EUR',
            'provider' => 'NewProvider'
        ];

        $response = $this->json('PUT', '/api/transactions/' . $this->transaction->id, $updatedData);

        $response->assertStatus(Response::HTTP_OK);

        $updatedTransaction = Transaction::find($this->transaction->id);

        $this->assertEquals($updatedData['amount'], $updatedTransaction->amount);
        $this->assertEquals($updatedData['currency'], $updatedTransaction->currency);
        $this->assertEquals($updatedData['provider'], $updatedTransaction->provider);
    }

    public function testUpdateTransaction_Error()
    {
        $this->createUser()->createTransaction();

        $updatedData = [
            'status' => -1,
        ];

        $response = $this->json('PUT', '/api/transactions/' . $this->transaction->id, $updatedData);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testDeleteTransaction_Success()
    {
        $this->createUser()->createTransaction();

        $response = $this->json('DELETE', '/api/transactions/' . $this->transaction->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull(Transaction::find($this->transaction->id));
    }

    public function testGetAllTransactions_Success()
    {
        Transaction::factory()->count(5)->create();

        $response = $this->json('GET', '/api/transactions');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5);
    }
}
