<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionService
{

    public function save($data)
    {
        Transaction::create($data);
    }

    public function getPrice($order_id)
    {
        $last_transaction = Transaction::where("order_id", $order_id)->orderBy("id", "DESC")->first();
        return $last_transaction->total;
    }

    public function addCoupon(Request $request, $order_id)
    {
        $total_price = $this->getPrice($order_id);

        $transaction = Transaction::where(["order_id" => $order_id, "reference_type" => "coupon", "reference_value" => $request->value])->first();

        if ($transaction) {
            $total_price_after_tax = $total_price;

            return ["message" => "Coupon already applied before.", "total_price" => $total_price_after_tax];
        } else {
            $tax = $total_price * 0.1;
            $total_price_after_tax = $total_price - $tax;

            $transction_arr = [
                "order_id" => $order_id,
                "reference_type" => "coupon",
                "reference_value" => $request->value,
                "reference_discount" => 0.1,
                "total" => $total_price_after_tax,
            ];

            $this->save($transction_arr);

            return ["message" => "Coupon applied successfully.", "total_price" => $total_price_after_tax];
        }

    }

}
