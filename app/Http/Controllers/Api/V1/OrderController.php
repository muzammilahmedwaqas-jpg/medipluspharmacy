<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items')->latest()->paginate(10);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = DB::transaction(function () use ($request) {
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'prescription_id' => $request->prescription_id,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->validated('items') as $item) {
                $medicine = Inventory::findOrFail($item['inventory_id']);

                $order->items()->create([
                    'inventory_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'price' => $medicine->price,
                ]);

                $total += $medicine->price * $item['quantity'];
            }

            $order->update(['total' => $total]);

            return $order;
        });

        return response()->json($order->load('items'), 201);
    }

    public function show(Order $order)
    {
        return $order->load('customer', 'items.inventory');
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return $order;
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->noContent();
    }
}