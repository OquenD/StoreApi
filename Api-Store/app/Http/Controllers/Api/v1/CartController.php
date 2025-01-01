<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Get authenticated user's cart with items and their related product variants
        $cart = auth()->user()->cart()->with(['items.productVariant.product'])->first();
        
        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        return response()->json($cart, 200);
    }

    public function addItem(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Get or create cart for the authenticated user
        $cart = auth()->user()->cart()->firstOrCreate();

        try {
            // Check if variant exists and is available
            $variant = ProductVariant::findOrFail($validatedData['variant_id']);
            
            // Check if item already exists in cart
            $cartItem = $cart->items()->where('product_variant_id', $variant->id)->first();
            
            if ($cartItem) {
                // Update quantity if item exists
                $cartItem->quantity += $validatedData['quantity'];
                $cartItem->save();
            } else {
                // Create new cart item
                $cartItem = $cart->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $validatedData['quantity']
                ]);
            }

            return response()->json([
                'message' => 'Item added to cart successfully',
                'cart_item' => $cartItem
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateItem(Request $request, $cartItemId)
    {
        // Validate request
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            // Find cart item and verify it belongs to user's cart
            $cartItem = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', auth()->id());
            })->findOrFail($cartItemId);

            // Update quantity
            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->save();

            return response()->json([
                'message' => 'Cart item updated successfully',
                'cart_item' => $cartItem
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating cart item',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function removeItem($cartItemId)
    {
        try {
            // Find cart item and verify it belongs to user's cart
            $cartItem = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', auth()->id());
            })->findOrFail($cartItemId);

            $cartItem->delete();

            return response()->json([
                'message' => 'Item removed from cart successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error removing item from cart',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}