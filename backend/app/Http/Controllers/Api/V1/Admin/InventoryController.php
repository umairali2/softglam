<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\JsonResponse;

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
}
