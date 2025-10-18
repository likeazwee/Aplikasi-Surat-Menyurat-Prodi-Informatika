<x-guest-layout>

    <!-- ✅ Pesan sukses setelah register -->
    @if (session('status'))
        <div class="w-full max-w-sm mx-auto mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-center shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                class="block mt-1 w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" 
                class="block mt-1 w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                type="password"
                name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me + Forgot Password -->
        <div class="block mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white py-3 rounded-lg shadow-md transition duration-200">
                {{ __('LOG IN') }}
            </x-primary-button>
        </div>

        <!-- Link daftar -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Daftar di sini</a>
        </p>
    </form>
</x-guest-layout>
