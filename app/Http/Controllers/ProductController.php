<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    private $product_service;

    public function __construct()
    {
        $this->product_service = new ProductService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product_service->getAll();
        return response()->json(["message" => "", "data" => $products]);
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
            'title' => 'required',
            'price' => "required",
            'discount' => "sometimes|lt:price",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        $response = $this->product_service->store($request);

        return response()->json(["message" => $response['message'], "data" => $response['data']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json(["message" => "", "data" => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => "required",
            'discount' => "sometimes|lt:price",
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()->first(), "data" => []], 400);
        }

        $response = $this->product_service->update($request, $product);

        return response()->json(["message" => $response['message'], "data" => $response['data']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->product_service->delete($product);

        return response()->json(["message" => "Product Deleted Successfully.", "data" => []]);
    }
}
