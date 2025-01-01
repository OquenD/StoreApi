<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get the logged in user's orders, using model relation.
        $orders = auth()->user()->orders;
        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Get the authenticated user's cart with items
            $cart = Cart::where('user_id', auth()->id())
                       ->with('items')
                       ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El carrito está vacío'
                ], 400);
            }

            // Start database transaction
            DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $cart->total,
                'status' => 'pending'
            ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price
                ]);
            }

            // Clear the cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            // Load the order with its items
            $order->load('items');

            return response()->json([
                'status' => 'success',
                'message' => 'Orden creada exitosamente',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Get user orders by id
        $order = Order::with('items')->find($id);

        //Use Gate so that the logged in user can see only their orders. Gate rules are in the boot method of the AppServiceProviders class
        if (! Gate::allows('user-view-order', $order)) {
            return response()->json(['message' => 'Sorry, You dont have access to this resources'], 403);
        }

        return response()->json($order,200);
    }

  
}
