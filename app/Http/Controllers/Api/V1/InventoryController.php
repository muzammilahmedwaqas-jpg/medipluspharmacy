<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        return Inventory::latest()->paginate(10);
    }

    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->validated());

        return response()->json($inventory, 201);
    }

    public function show(Inventory $inventory)
    {
        return $inventory;
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->validated());

        return $inventory;
    }

    public function destroy(Inventory $inventory)
    {
        if ($inventory->orderItems()->exists()) {
            return response()->json([
                'message' => 'This medicine appears in past orders and cannot be deleted.',
            ], 409);
        }

        $inventory->delete();

        return response()->noContent();
    }
}