<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Services\OrderProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderProductController extends Controller
{
    private $order_product_repo;

    public function __construct()
    {
        $this->order_product_repo = new OrderProductService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($order_id)
    {
        $order_products = OrderProduct::with("product")->where("order_id", $order_id)->get();

        return response()->json(["message" => "", "data" => $order_products]);
    }

    public function store(Request $request, $order_id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => "required",
            "quantity" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        $order_details = $this->order_product_repo->saveSingleProduct($order_id, $request->all());

        return response()->json(["message" => "Product added successfully into Cart.", "data" => $order_details]);
    }

    public function destroy($order_id, $product_id)
    {
        $order_details = $this->order_product_repo->removeProduct($order_id, $product_id);

        return response()->json(["message" => "Product removed successfully from Cart.", "data" => $order_details]);
    }
}
