<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = $this->publicQuery()
            ->when(
                $request->query('category'),
                fn (Builder $query, string $slug) => $query->whereHas(
                    'categories',
                    fn (Builder $q) => $q->where('slug', $slug),
                ),
            )
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->cursorPaginate(20);

        return ProductResource::collection($products);
    }

    public function show(string $slug): ProductResource
    {
        return new ProductResource(
            $this->publicQuery()->where('slug', $slug)->firstOrFail(),
        );
    }

    private function publicQuery(): Builder
    {
        return Product::query()
            ->published()
            ->with([
                'brand:id,name,slug',
                'productType:id,code,name',
                'categories:id,name,slug',
                'images:id,product_id,path,alt,position',
                'variants' => fn (HasMany $query) => $query
                    ->sellable()
                    ->orderByDesc('is_default')
                    ->orderBy('id'),
                'variants.inventoryItems:id,product_variant_id,location_id,on_hand,reserved',
                'variants.inventoryItems.location:id,is_active',
            ]);
    }
}
