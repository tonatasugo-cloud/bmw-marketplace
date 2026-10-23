<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = Seller::with('user');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('store_name', 'like', '%' . $request->q . '%')
                  ->orWhere('city', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(Seller $seller)
    {
        $seller->load(['user', 'cars', 'documents', 'reviews']);
        return view('admin.sellers.show', compact('seller'));
    }

    public function edit(Seller $seller)
    {
        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'store_name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'province'    => 'required|string',
            'phone'       => 'required|string',
            'whatsapp'    => 'nullable|string',
            'status'      => 'required|in:pending,verified,rejected,suspended',
        ]);

        $seller->update($validated);

        return redirect()->route('admin.sellers.index')
            ->with('success', 'Seller berhasil diperbarui.');
    }

    public function verify(Seller $seller)
    {
        $seller->update([
            'status'             => 'verified',
            'identity_verified'  => true,
            'phone_verified'     => true,
            'email_verified'     => true,
            'documents_verified' => true,
            'joined_at'          => $seller->joined_at ?? now(),
        ]);

        // Assign role seller ke user
        $seller->user->assignRole('seller');

        return back()->with('success', 'Seller berhasil diverifikasi.');
    }

    public function reject(Request $request, Seller $seller)
    {
        $seller->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Seller ditolak.');
    }

    public function suspend(Seller $seller)
    {
        $seller->update(['status' => 'suspended']);

        return back()->with('success', 'Seller di-suspend.');
    }

    public function destroy(Seller $seller)
    {
        if ($seller->cars()->count() > 0) {
            return back()->withErrors(['msg' => 'Seller masih punya listing aktif.']);
        }

        $seller->delete();

        return redirect()->route('admin.sellers.index')
            ->with('success', 'Seller dihapus.');
    }
}