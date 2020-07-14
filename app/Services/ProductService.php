<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function getAll()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $final_price = $request->price;
        if (isset($request->discount) && $request->discount != "") {
            $final_price = $request->price - $request->discount;
        }

        $product = Product::create([
            "title" => $request->title,
            "price" => $request->price,
            "discount" => $request->discount,
            "final_price" => $final_price,
        ]);

        return ["message" => "", "data" => $product];
    }

    public function update(Request $request, $product)
    {
        $final_price = $product->price;
        if (isset($request->discount) && $request->discount != "") {
            $final_price = $request->price - $request->discount;
        }

        $product->update([
            "title" => $request->title,
            "price" => $request->price,
            "discount" => $request->discount,
            "final_price" => $final_price,
        ]);

        return ["message" => "", "data" => $product];
    }

    public function delete($product)
    {
        $product->delete();
    }
}
