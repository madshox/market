<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Domain\Basket\Requests\BasketAddRequest;
use App\Domain\Basket\Requests\BasketRemoveRequest;

class BasketController extends BaseController
{
    public function addProduct(BasketAddRequest $request)
    {
        try {
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
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }

        return response()->json(['message' => 'Product added to basket'], 200);
    }

    public function removeProduct(BasketRemoveRequest $request)
    {
        try {
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
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
    }
}
