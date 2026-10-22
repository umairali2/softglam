<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WishlistController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->published()
            ->whereIn('id', WishlistItem::query()
                ->where('user_id', $request->user()->id)
                ->select('product_id'))
            ->with(['variants' => fn ($q) => $q->sellable()->orderByDesc('is_default'), 'images'])
            ->get();

        return ProductResource::collection($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'uuid', 'exists:products,id'],
        ]);

        WishlistItem::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
        ]);

        return response()->json(null, 204);
    }

    public function destroy(Request $request, string $productId): JsonResponse
    {
        WishlistItem::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(null, 204);
    }
}
