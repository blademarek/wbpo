<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Rules\ValidTransactionStatusTransition;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Display a listing of Transactions.
     */
    public function index()
    {
        return $this->transactionRepository->getAllTransactions();
    }

    /**
     * Store new Transaction.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'provider' => 'required|string',
                'user_id' => 'required|exists:users,id'
            ]);

            $transaction = $this->transactionRepository->createTransaction($validatedData);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create transaction: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($transaction, Response::HTTP_CREATED);
    }

    /**
     * Display Transaction.
     */
    public function show(string $id)
    {
        return $this->transactionRepository->getTransactionById($id);
    }

    /**
     * Update Transaction.
     * @throws Exception
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $transaction = Transaction::findOrFail($id);

            $validatedData = $request->validate([
                'amount' => 'numeric',
                'currency' => 'string',
                'provider' => 'string',
                'user_id' => 'exists:users,id',
                'status' => ['numeric', new ValidTransactionStatusTransition($transaction)]
            ]);

            $this->transactionRepository->updateTransaction($id, $validatedData);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update transaction: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($transaction, Response::HTTP_OK);
            }

    /**
     * Remove Transaction.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->transactionRepository->deleteTransaction($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
