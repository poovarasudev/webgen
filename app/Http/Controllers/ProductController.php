<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            $inputs = $request->only(['name', 'price', 'description']);
            // Note user_id will be store in boot method of this model.
            Product::create($inputs);
            return response()->json(['success' => 'Product created successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Error while creating new product.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->update($request->only(['name', 'price', 'description']));
            return response()->json(['success' => 'Product updated successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Error while updating new product.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['success' => 'Product delete successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Error while deleting given product.'], 500);
        }
    }

    /**
     * Get products for listing in yajra.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request)
    {
        return DataTables::eloquent(Product::with('user')->latest())
            ->editColumn('created_at', function ($product) {
                return $product->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function($data) {
                return '<button type="button" class="btn btn-success btn-sm" onclick=editProduct("' . $data->id . '")>Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick=deleteProduct("' . $data->id . '")>Delete</button>';
            })
            ->setRowId('id')
            ->make(true);
    }
}
