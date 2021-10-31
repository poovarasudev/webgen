<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::with('user')->latest()->get();
        return response()->json((new ProductTransformer())->transformProductList($products));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            $inputs = $request->only(['name', 'price', 'description']);
            // Note user_id will be store in boot method of this model.
            $product = Product::create($inputs);
            return response()->json(['status' => 'success', 'message' => 'Product created successfully.', 'data' => (new ProductTransformer())->transform($product)]);
        } catch (\Exception $exception) {
            Log::error("Error while creating product", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, getRouteNameForError() . '-INTERNAL_SERVER_ERROR', 'Error while creating product.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->update($request->only(['name', 'price', 'description']));
            return response()->json(['status' => 'success', 'message' => 'Product updated successfully.', 'data' => (new ProductTransformer())->transform($product)]);
        } catch (\Exception $exception) {
            Log::error("Error while updating product", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, getRouteNameForError() . '-INTERNAL_SERVER_ERROR', 'Error while updating product.');
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
            return response()->json(['status' => 'success', 'message' => 'Product deleted successfully.']);
        } catch (\Exception $exception) {
            Log::error("Error while deleting product", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, getRouteNameForError() . '-INTERNAL_SERVER_ERROR', 'Error while deleting given product.');
        }
    }
}
