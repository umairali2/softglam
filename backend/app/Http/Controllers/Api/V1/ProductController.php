<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = $this->publicQuery()
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
                'variants' => fn (HasMany $query) => $query
                    ->sellable()
                    ->orderByDesc('is_default')
                    ->orderBy('id'),
            ]);
    }
}
