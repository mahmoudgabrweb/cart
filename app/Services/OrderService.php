<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function getAll()
    {
        return Order::all();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                "shipping_method" => $request->shipping_method,
                "payment_method" => $request->payment_method,
            ]);

            $orderProductService = new OrderProductService();
            $orderProductService->saveProducts($order->id, $request->products);

            $taxService = new TaxService();
            $order->total = $taxService->calculateTaxes($order);

            $order->status = config("orders.status.1");
            $order->shipping_method = config("orders.shipping_methods.{$order->shipping_method}.title");
            $order->payment_method = config("orders.payment_methods.{$order->payment_method}");

            DB::commit();

            return ["message" => "", "data" => $order];
        } catch (\Exception $e) {
            DB::rollback();
            return ["message" => "Problem while adding", "data" => []];
        }
    }

    public function show($order)
    {
        $order->status = config("orders.status.{$order->status}");
        $order->shipping_method = config("orders.shipping_methods.{$order->shipping_method}.title");
        $order->payment_method = config("orders.payment_methods.{$order->payment_method}");
        $transactionService = new TransactionService();
        $order->price = $transactionService->getPrice($order->id);

        return $order;
    }

    public function update(Request $request, $order)
    {
        DB::beginTransaction();

        try {
            $order->update([
                "shipping_method" => $request->shipping_method,
                "payment_method" => $request->payment_method,
            ]);

            $orderProductService = new OrderProductService();
            $orderProductService->updateProducts($order->id, $request->products);

            $taxService = new TaxService();
            $order->total = $taxService->calculateTaxes($order);

            $order->status = config("orders.status.{$order->status}");
            $order->shipping_method = config("orders.shipping_methods.{$order->shipping_method}.title");
            $order->payment_method = config("orders.payment_methods.{$order->payment_method}");
            DB::commit();

            return ["message" => "", "data" => $order];

        } catch (\Exception $e) {
            DB::rollback();
            return ["message" => "Problem while adding", "data" => []];
        }
    }

    public function delete($order)
    {
        $order->delete();
    }

    public function updateStatus($order_id, $status)
    {
        $order = Order::find($order_id);
        $order->update(['status' => $status]);
        $order->status = config("orders.status.{$order->status}");
        $order->shipping_method = config("orders.shipping_methods.{$order->shipping_method}.title");
        $order->payment_method = config("orders.payment_methods.{$order->payment_method}");
        return $order;
    }
}
