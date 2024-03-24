<?php

namespace App\Repositories;

use App\Events\TransactionCreated;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAllTransactions()
    {
        return Transaction::all()->toArray();
    }

    public function getTransactionById($transactionId)
    {
        return Transaction::findOrFail($transactionId);
    }

    public function deleteTransaction($transactionId): int
    {
        $transaction = Transaction::destroy($transactionId);
        Log::info("Transaction deleted", ['transaction_id' => $transactionId]);
        return $transaction;
    }

    /**
     * @throws Exception
     */
    public function createTransaction(array $transactionDetails)
    {
        DB::beginTransaction();
        Log::info('Creating transaction', $transactionDetails);

        try {
            $transaction = Transaction::create($transactionDetails);
            DB::commit();

            event(new TransactionCreated($transaction));

            return $transaction;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Transaction creation failed');

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function updateTransaction($transactionId, array $newDetails)
    {
        DB::beginTransaction();

        try {
            $transaction = $this->getTransactionById($transactionId);
            $transaction->update($newDetails);
            DB::commit();

            Log::info("Transaction updated with ID: $transactionId", $newDetails);

            return $transaction;
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Transaction update failed for ID: $transactionId");

            throw $e;
        }
    }
}
