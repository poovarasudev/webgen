<?php

namespace App\Transformers;

use App\Models\Product;

class ProductTransformer
{
    /**
     * Get the json format for list of product.
     *
     * @param $products
     * @return array
     */
    public function transformProductList($products)
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = $this->transform($product);
        }
        return $data;
    }

    /**
     * Get the json format of product.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'created_by' => $product->user->name,
            'created_by_id' => $product->user_id,
            'price' => $product->price,
            'description' => $product->description,
        ];
    }
}
