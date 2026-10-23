<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'orders', 'seller']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended,pending',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function suspend(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->withErrors(['msg' => 'Admin tidak bisa di-suspend.']);
        }

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'User di-suspend.');
    }

    public function activate(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('success', 'User diaktifkan.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->withErrors(['msg' => 'Admin tidak bisa dihapus.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password user berhasil di-reset.');
    }
}