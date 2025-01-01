<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductVarientImagResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 'success',
            'message' => 'All products list retrieved successfully',
            'products' => $products,
        ]);
    }

    public function recentproduct()
    {
        $products = Product::orderBy('created_at', 'desc')->take(4)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Latest products list retrieved successfully',
            'products' => $products,
        ]);
    }


    public function show($id)
    {
        try {
            $product = Product::with('variants.variantImages')->find($id);
            Log::info('Product retrieved successfully');
            //return success
            return response()->json([
                'status' => 'success',
                'message' => 'Product retrieved successfully',
                'data' => [
                    'product' => new ProductDetailsResource($product),
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'color' => $variant->color,
                        ];
                    }),
                    'variant_images' => $product->variants->flatMap(function ($variant) {
                        return $variant->variantImages->map(function ($variantImage) {
                            return [
                                'id' => $variantImage->id,
                                'variant_id' => $variantImage->varient_id,
                                'image_url' => $variantImage->image_url,
                            ];
                        });
                    })
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found' . $th->getMessage(),
            ]);
        }
    }
}
