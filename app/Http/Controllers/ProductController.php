<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    //api to show single product
    public function show($id)
    {
        $product = Product::where('id', $id)
        ->select('name', 'price', 'description')
        ->findOrFail($id);
        $out['status'] = 'success';
        $out['results'] = $product;
        return response($out, 200);
    }

    //api to show product list
    public function index()
    {
        $product = Product::select('name', 'price', 'description')->get();
        $out['statuc'] = 'success';
        $out['results'] = $product;
        return response($out, 200);
    }
    
    //api to add products
    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string'
        ]);
        $product = Product::create($request->all());
        $out['status'] = 'success';
        return response($out, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        $out['status'] = 'success';
        $out['result'] = $product;
        return response($out, 200);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete($id);
        $out['status'] = 'success';
        $out['result'] = 'product deleted.';
        return response($out, 200);
    }
}
