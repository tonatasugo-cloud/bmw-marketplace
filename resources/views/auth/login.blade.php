<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Bavaria Select</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-black text-white">
                BAVARIA<span class="text-blue-400">SELECT</span>
            </a>
            <p class="text-gray-400 mt-2 text-sm">Premium BMW Pre-Owned Marketplace</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h1 class="text-2xl font-black mb-6">Masuk ke Akun</h1>

            @if(session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3 border focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded">
                        <span>Ingat saya</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    MASUK
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                    Daftar Sekarang
                </a>
            </p>

            <div class="mt-6 pt-6 border-t text-xs text-gray-500">
                <p class="font-semibold mb-2">Demo Login:</p>
                <p>🔑 Admin: <code>admin@bmw.test</code> / <code>password</code></p>
                <p>🔑 Seller: <code>seller1@bmw.test</code> / <code>password</code></p>
            </div>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">
            © {{ date('Y') }} Bavaria Select
        </p>
    </div>

</body>
</html>