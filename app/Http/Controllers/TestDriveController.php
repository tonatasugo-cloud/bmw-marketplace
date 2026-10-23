<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestDriveController extends Controller
{
    public function index()
    {
        $seller = Auth::user()->seller;

        $testDrives = TestDrive::whereHas('car', fn($q) => $q->where('seller_id', $seller->id))
            ->with(['car.primaryImage', 'user'])
            ->latest()
            ->paginate(15);

        return view('seller.test-drives.index', compact('testDrives'));
    }

    public function respond(Request $request, TestDrive $testDrive)
    {
        if ($testDrive->car->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status'   => 'required|in:confirmed,rejected,completed,cancelled',
            'response' => 'nullable|string|max:500',
        ]);

        $testDrive->update([
            'status'          => $validated['status'],
            'seller_response' => $validated['response'] ?? null,
        ]);

        return back()->with('success', 'Test drive diperbarui.');
    }
}