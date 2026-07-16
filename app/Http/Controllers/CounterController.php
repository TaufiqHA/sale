<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Counter::all());
        }

        return view('administrator.counter');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $counter = Counter::create($validated);

        return response()->json($counter, 201);
    }

    /**
     * Alias for store.
     */
    public function create(Request $request): JsonResponse
    {
        return $this->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Counter $counter): JsonResponse
    {
        return response()->json($counter);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Counter $counter): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ]);

        $counter->update($validated);

        return response()->json($counter);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Counter $counter): JsonResponse
    {
        $counter->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Counter $counter): JsonResponse
    {
        return $this->destroy($counter);
    }
}
