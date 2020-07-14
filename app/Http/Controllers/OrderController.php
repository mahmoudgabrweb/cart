<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $order_service;

    public function __construct()
    {
        $this->order_service = new OrderService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orders = $this->order_service->getAll();
        return response()->json(["message" => "", "data" => $orders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_method' => "required",
            "payment_method" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        if ($request->shipping_method == 1 && $request->payment_method == 1) {
            return response()->json(['message' => "Cash On Delivery: Works only with Home Delivery Method", "data" => []], 400);
        }

        if ($request->shipping_method == 2 && $request->payment_method == 2) {
            return response()->json(['message' => "Pay At Store: Works only with Pickup From Store Method", "data" => []], 400);
        }

        $response = $this->order_service->store($request);

        return response()->json(["message" => $response['message'], "data" => $response['data']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order_details = $this->order_service->show($order);
        return response()->json(["message" => "", "data" => $order_details]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'shipping_method' => "required",
            "payment_method" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        if ($request->shipping_method == 1 && $request->payment_method == 1) {
            return response()->json(['message' => "Cash On Delivery: Works only with Home Delivery Method", "data" => []], 400);
        }

        if ($request->shipping_method == 2 && $request->payment_method == 2) {
            return response()->json(['message' => "Pay At Store: Works only with Pickup From Store Method", "data" => []], 400);
        }

        $response = $this->order_service->store($request, $order);

        return response()->json(["message" => $response['message'], "data" => $response['data']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $this->order_service->delete($order);
        return response()->json(["message" => "Product Deleted Successfully.", "data" => []]);
    }

    public function updateStatus($order_id, $status)
    {
        $order = $this->order_service->updateStatus($order_id, $status);
        return response(['message' => "", "data" => $order]);
    }
}
