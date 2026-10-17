<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpsertCustomerAddressRequest;
use App\Http\Resources\Api\V1\CustomerAddressResource;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $addresses = $request->user()->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return CustomerAddressResource::collection($addresses);
    }

    public function store(UpsertCustomerAddressRequest $request): CustomerAddressResource
    {
        $address = DB::transaction(function () use ($request): CustomerAddress {
            $this->clearDefault($request);

            return $request->user()->addresses()->create($request->validated());
        });

        return new CustomerAddressResource($address);
    }

    public function update(UpsertCustomerAddressRequest $request, string $address): CustomerAddressResource
    {
        $address = $request->user()->addresses()->findOrFail($address);

        DB::transaction(function () use ($request, $address): void {
            $this->clearDefault($request);
            $address->update($request->validated());
        });

        return new CustomerAddressResource($address->fresh());
    }

    public function destroy(Request $request, string $address): Response
    {
        $request->user()->addresses()->findOrFail($address)->delete();

        return response()->noContent();
    }

    private function clearDefault(Request $request): void
    {
        if ($request->boolean('is_default')) {
            $request->user()->addresses()->update(['is_default' => false]);
        }
    }
}
