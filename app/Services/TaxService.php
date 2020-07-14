<?php
namespace App\Services;

use App\Models\OrderProduct;

class TaxService
{

    public function calculateTaxes($order)
    {
        $total_price = $this->saveTax($order->id);
        $total = $this->saveShippingTax($order->id, $total_price, config("orders.shipping_methods.{$order->shipping_method}.amount"));
        return $total;
    }

    public function saveTax($order_id)
    {
        $price_after_tax = $this->calculate($order_id);

        $transction_arr = [
            "order_id" => $order_id,
            "reference_type" => "tax",
            "reference_value" => "tax",
            "reference_discount" => 0.1,
            "total" => $price_after_tax,
        ];

        $transaction_service = new TransactionService();
        $transaction_service->save($transction_arr);

        return $price_after_tax;
    }

    private function calculate($order_id)
    {
        $order_products = OrderProduct::with("product")->where("order_id", $order_id)->get();
        $total = 0;
        foreach ($order_products as $order_product) {
            $total += ($order_product->product->final_price * $order_product->quantity);
        }
        $tax = $total * 0.1;
        $total_price_after_tax = $total - $tax;
        return $total_price_after_tax;
    }

    public function saveShippingTax($order_id, $total_price, $shipping_tax)
    {
        $tax = $total_price * $shipping_tax;
        $total_price_after_tax = $total_price - $tax;

        $transction_arr = [
            "order_id" => $order_id,
            "reference_type" => "shipping_tax",
            "reference_value" => "shipping_tax",
            "reference_discount" => $shipping_tax,
            "total" => $total_price_after_tax,
        ];

        $transaction_service = new TransactionService();
        $transaction_service->save($transction_arr);

        return $total_price_after_tax;
    }
}
