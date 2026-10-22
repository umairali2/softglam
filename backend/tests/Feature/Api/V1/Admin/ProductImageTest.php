<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $staff;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);
        Storage::fake(ProductImage::DISK);

        $this->product = Product::factory()->published()->create();
        $this->staff = User::factory()->create([
            'email' => 'staff@softglam.pk',
            'password' => 'password',
            'is_staff' => true,
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email' => $this->staff->email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_staff_can_upload_a_product_image(): void
    {
        $response = $this->post("/api/v1/admin/products/{$this->product->id}/images", [
            'image' => UploadedFile::fake()->image('hoops.jpg', 800, 800),
            'alt' => 'Gold hoop earrings',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.alt', 'Gold hoop earrings');

        $image = $this->product->images()->sole();
        Storage::disk(ProductImage::DISK)->assertExists($image->path);
        $this->assertStringNotContainsString('hoops.jpg', $image->path);
    }

    public function test_non_staff_cannot_upload(): void
    {
        $customer = User::factory()->create(['email' => 'c@example.com', 'password' => 'password']);
        $this->postJson('/api/v1/auth/login', [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertOk();

        $this->post("/api/v1/admin/products/{$this->product->id}/images", [
            'image' => UploadedFile::fake()->image('x.jpg'),
        ])->assertForbidden();
    }

    public function test_rejects_non_images_and_oversized_files(): void
    {
        $this->post("/api/v1/admin/products/{$this->product->id}/images", [
            'image' => UploadedFile::fake()->create('shell.php', 10),
        ])->assertUnprocessable();
    }

    public function test_deleting_removes_the_file_and_row(): void
    {
        $image = $this->product->images()->create([
            'path' => 'products/test/file.jpg',
            'position' => 1,
        ]);
        Storage::disk(ProductImage::DISK)->put('products/test/file.jpg', 'x');

        $this->deleteJson("/api/v1/admin/products/{$this->product->id}/images/{$image->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk(ProductImage::DISK)->assertMissing('products/test/file.jpg');
    }
}
