<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private $transaction_service;

    public function __construct()
    {
        $this->transaction_service = new TransactionService();
    }

    public function addCoupon(Request $request, $order_id)
    {
        $validator = Validator::make($request->all(), [
            "value" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        $response = $this->transaction_service->addCoupon($request, $order_id);

        return response()->json(["message" => $response['message'], "data" => ["total_price" => $response['total_price']]]);
    }
}
