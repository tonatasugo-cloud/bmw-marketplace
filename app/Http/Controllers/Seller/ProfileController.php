<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $seller = Auth::user()->seller;

        return view('seller.profile', compact('seller'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $seller = $user->seller;

        $validated = $request->validate([
            'store_name'        => 'required|string|max:255',
            'description'       => 'nullable|string|max:1000',
            'address'           => 'required|string|max:500',
            'city'              => 'required|string|max:100',
            'province'          => 'required|string|max:100',
            'postal_code'       => 'nullable|string|max:10',
            'phone'             => 'required|string|max:20',
            'whatsapp'          => 'nullable|string|max:20',
            'operational_hours' => 'nullable|string|max:100',
            'logo'              => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('seller-logos', 'public');
        }

        if ($seller) {
            $seller->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            $validated['slug'] = Str::slug($validated['store_name']) . '-' . $user->id;
            $validated['status'] = 'pending';
            Seller::create($validated);
        }

        return redirect()->route('seller.dashboard')
            ->with('success', 'Profil showroom berhasil disimpan!');
    }
}