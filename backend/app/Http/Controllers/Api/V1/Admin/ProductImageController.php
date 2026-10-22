<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'alt' => ['nullable', 'string', 'max:160'],
        ]);

        $path = $request->file('image')->store("products/{$product->id}", ProductImage::DISK);

        $image = $product->images()->create([
            'path' => $path,
            'alt' => $validated['alt'] ?? $product->title,
            'position' => (int) $product->images()->max('position') + 1,
        ]);

        return response()->json(['data' => [
            'id' => $image->id,
            'url' => $image->url(),
            'alt' => $image->alt,
            'position' => $image->position,
        ]], 201);
    }

    public function destroy(Product $product, ProductImage $image): JsonResponse
    {
        abort_unless($image->product_id === $product->id, 404);
        $image->delete();

        return response()->json(null, 204);
    }
}
