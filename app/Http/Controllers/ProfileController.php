<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the profile settings page.
     */
    public function index(Request $request): View
    {
        return view('administrator.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'user' => $user->fresh(),
            ]);
        }

        return redirect()->route('administrator.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kata sandi berhasil diperbarui.',
            ]);
        }

        return redirect()->route('administrator.profile')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
