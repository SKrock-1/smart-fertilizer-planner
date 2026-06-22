<x-guest-layout>
    <!-- Session Status (handled globally by Toast in layout, but keeping this fallback empty so it doesn't duplicate) -->
    
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
        <p class="text-gray-500">Sign in to your account to continue planning.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="farmer@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm font-medium" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm font-medium" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center cursor-pointer group">
                <div class="relative flex items-center justify-center">
                    <input id="remember_me" type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md checked:bg-[#2D6A4F] checked:border-[#2D6A4F] focus:outline-none focus:ring-4 focus:ring-[#2D6A4F]/20 transition-all cursor-pointer">
                    <svg class="absolute w-3.5 h-3.5 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ms-3 text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">Remember for 30 days</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-[#2D6A4F] hover:text-[#1B4332] transition-colors" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#d9ef6f]/20 text-sm font-bold text-[#1B4332] bg-[#d9ef6f] hover:bg-[#c5df55] hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[#d9ef6f]/50 transition-all duration-200">
            Sign In
        </button>
        
        <p class="text-center text-sm font-medium text-gray-600 mt-8">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-[#2D6A4F] hover:text-[#1B4332] transition-colors">Register for free</a>
        </p>
    </form>
</x-guest-layout>
