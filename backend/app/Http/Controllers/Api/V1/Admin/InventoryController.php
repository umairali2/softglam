<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Inventory\AdjustInventory;
use App\Exceptions\InvalidInventoryAdjustment;
use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function lowStock(): JsonResponse
    {
        $items = InventoryItem::query()
            ->with(['productVariant:id,product_id,sku,title', 'productVariant.product:id,title,slug', 'location:id,name'])
            ->whereRaw('(on_hand - reserved) <= low_stock_threshold')
            ->get()
            ->map(fn (InventoryItem $item) => [
                'inventory_item_id' => $item->id,
                'sku' => $item->productVariant->sku,
                'product_title' => $item->productVariant->product->title,
                'variant_title' => $item->productVariant->title,
                'location' => $item->location->name,
                'on_hand' => $item->on_hand,
                'reserved' => $item->reserved,
                'available' => $item->availableQuantity(),
                'low_stock_threshold' => $item->low_stock_threshold,
            ])
            ->sortBy('available')
            ->values();

        return response()->json(['data' => $items]);
    }

    public function adjust(Request $request, InventoryItem $inventoryItem, AdjustInventory $adjustInventory): JsonResponse
    {
        $validated = $request->validate([
            'delta' => ['required', 'integer', 'not_in:0'],
            'reason' => ['required', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $movement = $adjustInventory->handle(
                $inventoryItem->productVariant,
                $inventoryItem->location,
                (int) $validated['delta'],
                $validated['reason'],
                $request->user(),
                $validated['note'] ?? null,
            );
        } catch (InvalidInventoryAdjustment $e) {
            throw ValidationException::withMessages(['delta' => [$e->getMessage()]]);
        }

        return response()->json([
            'data' => [
                'id' => $movement->id,
                'inventory_item_id' => $movement->inventory_item_id,
                'on_hand_after' => $movement->on_hand_after,
                'reserved_after' => $movement->reserved_after,
            ],
        ], 201);
    }
}
