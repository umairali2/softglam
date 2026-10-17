<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['data' => ['status' => 'already_verified']]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['data' => ['status' => 'sent']], 202);
    }

    public function update(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        $status = 'invalid';

        if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
            if (! $user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            $status = 'verified';
        }

        return redirect()->away(
            rtrim((string) config('app.frontend_url'), '/').'/verify-email?status='.$status
        );
    }
}
