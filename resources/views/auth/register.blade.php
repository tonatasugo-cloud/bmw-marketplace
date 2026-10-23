<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Nicolas Select</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 flex items-center justify-center p-4">

    <div class="w-full max-w-md my-8">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-black text-white">
                NICOLAS<span class="text-blue-400">SELECT</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h1 class="text-2xl font-black mb-6">Buat Akun Baru</h1>

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Daftar Sebagai</label>
                    <select name="role" required
                            class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500">
                        <option value="buyer" @selected(old('role') == 'buyer')>🛒 Pembeli (Buyer)</option>
                        <option value="seller" @selected(old('role') == 'seller')>🏪 Penjual (Seller)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Pilih "Penjual" jika Anda memiliki showroom/dealer BMW.
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    DAFTAR
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

</body>
</html>