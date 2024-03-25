<?php

namespace Tests\Unit;

use App\Events\TransactionCreated;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Event;
use Tests\BaseWbpoTest;

class TransactionTest extends BaseWbpoTest
{
    protected TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = new TransactionRepository();
    }

    public function testCreateTransaction()
    {
        $this->createUser();

        $transaction = $this->transactionRepository->createTransaction($this->getTransactionDetails());

        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testGetTransactionById()
    {
        $this->createUser()->createTransaction();

        $retrievedTransaction = $this->transactionRepository->getTransactionById($this->transaction->id);

        $this->assertEquals($this->transaction->id, $retrievedTransaction->id);
    }

    public function testDeleteTransaction()
    {
        $this->createUser()->createTransaction();

        $deleted = $this->transactionRepository->deleteTransaction($this->transaction->id);

        $this->assertEquals(1, $deleted);
        $this->assertNull(Transaction::find($this->transaction->id));
    }

    public function testUpdateTransaction()
    {
        $this->createUser()->createTransaction();

        $updatedDetails = [
            'amount' => 200,
            'currency' => 'EUR',
            'provider' => 'PayPal'
        ];

        $this->transactionRepository->updateTransaction($this->transaction->id, $updatedDetails);

        $updatedTransaction = Transaction::find($this->transaction->id);

        $this->assertEquals(200, $updatedTransaction->amount);
        $this->assertEquals('EUR', $updatedTransaction->currency);
        $this->assertEquals('PayPal', $updatedTransaction->provider);
    }

    public function testEventCreated()
    {
        Event::fake(TransactionCreated::class);

        $this->createUser();

        $this->transactionRepository->createTransaction($this->getTransactionDetails());

        Event::assertDispatched(TransactionCreated::class);
    }
}
