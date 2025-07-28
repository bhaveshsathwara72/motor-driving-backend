<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Create a new transaction record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create transaction record
        $transaction = Transaction::create([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transaction recorded successfully',
            'data' =>$transaction
        ], 201);
    }

    /**
     * Get all transaction records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions()
    {
        $transactions = Transaction::with('booking','booking.customer','booking.package','booking.package.vehicle')->get();
        return response()->json([
            'status' => true,
            'message' => 'Transactions retrieved successfully',
            'data' =>$transactions
        ]);
    }

    /**
     * Get a specific transaction record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransaction($id)
    {
        $transaction = Transaction::with('booking')->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction record not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ]);
    }

    /**
     * Update a transaction record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTransaction(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction record not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'booking_id' => 'sometimes|required|exists:bookings,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update transaction record
        if ($request->has('booking_id')) {
            $transaction->booking_id = $request->booking_id;
        }
        if ($request->has('amount')) {
            $transaction->amount = $request->amount;
        }
        if ($request->has('date')) {
            $transaction->date = $request->date;
        }

        $transaction->save();

        return response()->json([
            'status' => true,
            'message' => 'Transaction record updated successfully',
            'data' =>$transaction
        ]);
    }

    /**
     * Delete a transaction record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction record not found',
                'data' => null
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaction record deleted successfully',
            'data' => null
        ]);
    }
}