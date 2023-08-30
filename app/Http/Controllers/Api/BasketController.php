<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Domain\Basket\Requests\BasketAddRequest;
use App\Domain\Basket\Requests\BasketRemoveRequest;

class BasketController extends Controller
{
    public function addProduct(BasketAddRequest $request)
    {
        $user = auth()->user();
        $product = Product::find($request->post('product_id'));

        $basketItem = $user->basket()->where('product_id', $product->id)->first();
        if ($basketItem) {
            $basketItem->update(['quantity' => $basketItem->quantity + $request->input('quantity')]);
        } else {
            $user->basket()->create([
                'product_id' => $product->id,
                'quantity' => $request->input('quantity'),
            ]);
        }

        return response()->json(['message' => 'Product added to basket'], 200);
    }

    public function removeProduct(BasketRemoveRequest $request)
    {
        $user = auth()->user();
        $basketItem = $user->basket()->where('product_id', $request->post('product_id'))->first();

        if ($basketItem) {
            if ($basketItem->quantity > 1) {
                $basketItem->update(['quantity' => $basketItem->quantity - 1]);
            } else {
                $basketItem->delete();
            }

            return response()->json(['message' => 'Product removed from basket'], 200);
        } else {
            return response()->json(['message' => 'Product not found in basket'], 404);
        }
    }
}
