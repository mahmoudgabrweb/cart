<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderProductService
{
    public function saveProducts($order_id, $products)
    {
        $created_arr = [];
        foreach ($products as $product) {
            $created_arr[] = [
                "order_id" => $order_id, "product_id" => $product['product_id'], "quantity" => $product['quantity'], "created_at" => Carbon::now(), "updated_at" => Carbon::now(),
            ];
        }
        DB::table('order_products')->insert($created_arr);
    }

    public function updateProducts($order_id, $products)
    {
        foreach ($products as $product) {
            OrderProduct::where(['order_id' => $order_id, "product_id" => $product['product_id']])
                ->update(["quantity" => $product['quantity']]);
        }
    }

    public function saveSingleProduct($order_id, $product)
    {
        OrderProduct::updateOrCreate([
            'order_id' => $order_id,
            "product_id" => $product['product_id'],
        ], [
            "quantity" => $product['quantity'],
        ]);

        $order = Order::find($order_id);
        $taxService = new TaxService();
        $taxService->calculateTaxes($order);

        return OrderProduct::with("product")->where('order_id', $order_id)->get();
    }

    public function removeProduct($order_id, $product_id)
    {
        OrderProduct::where(['order_id' => $order_id, "product_id" => $product_id])->delete();

        $order = Order::find($order_id);
        $taxService = new TaxService();
        $taxService->calculateTaxes($order);

        return OrderProduct::with("product")->where('order_id', $order_id)->get();
    }
}
