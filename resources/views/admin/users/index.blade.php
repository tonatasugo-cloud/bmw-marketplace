@extends('layouts.admin')

@section('title', 'Kelola Users')
@section('page-title', 'Kelola Users')
@section('page-subtitle', 'Daftar semua pengguna platform')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    {{-- Header + Search --}}
    <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-bold text-lg">Total: {{ $users->total() }} users</h2>
        </div>

        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari nama / email..."
                   class="border rounded-lg px-4 py-2 text-sm w-64">
            <select name="role" class="border rounded-lg px-4 py-2 text-sm">
                <option value="">Semua Role</option>
                <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                <option value="seller" @selected(request('role') == 'seller')>Seller</option>
                <option value="buyer" @selected(request('role') == 'buyer')>Buyer</option>
            </select>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                <img src="{{ asset('storage/search2.png') }}" alt="Revenue" class="w-8 h-8 object-contain">
            </button>
            @if(request()->anyFilled(['q', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}"
                   class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->getRoleNames() as $role)
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $role === 'seller' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $role === 'buyer' ? 'bg-green-100 text-green-700' : '' }}">
                                    {{ $role }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->status ?? 'active' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200">
                                    Detail
                                </a>
                                @if(!$user->hasRole('admin'))
                                    @if($user->status === 'active')
                                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200">
                                                Suspend
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada user ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="p-4 border-t">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection