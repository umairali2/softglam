<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    public function index(): JsonResponse
    {
        $returns = OrderReturn::query()
            ->with(['order:id,order_number,contact_email,contact_phone,total_amount'])
            ->where('status', 'requested')
            ->oldest()
            ->get();

        return response()->json(['data' => $returns]);
    }

    public function resolve(Request $request, OrderReturn $return): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approve,decline,refund'],
            'note' => ['nullable', 'string', 'max:500'],
            'refund_amount' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($return->status === 'refunded' || $return->status === 'declined') {
            throw ValidationException::withMessages([
                'status' => ['This return has already been resolved.'],
            ]);
        }
        if ($validated['action'] === 'refund' && $return->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => ['Only approved returns can be marked refunded.'],
            ]);
        }
        if ($validated['action'] === 'refund') {
            $refundAmount = $validated['refund_amount'] ?? $return->order->total_amount;
            if ($refundAmount > $return->order->total_amount) {
                throw ValidationException::withMessages([
                    'refund_amount' => ['Refund cannot exceed the order total.'],
                ]);
            }
        }

        $status = match ($validated['action']) {
            'approve' => 'approved',
            'decline' => 'declined',
            'refund' => 'refunded',
        };

        $return->update([
            'status' => $status,
            'resolution_note' => $validated['note'] ?? null,
            'handled_by' => $request->user()->id,
            'refund_amount' => $validated['action'] === 'refund' ? ($validated['refund_amount'] ?? $return->order->total_amount) : null,
            'resolved_at' => now(),
        ]);
        $return->order->recordEvent("return_{$status}", $request->user(), $validated['note'] ?? null);

        return response()->json(['data' => $return->fresh()]);
    }
}
