<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create an account</h2>
        <p class="text-gray-500">Register your farm to start generating intelligent fertilizer plans.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="Ramesh Kumar">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-600 text-sm font-medium" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="farmer@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 text-sm font-medium" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="Create a strong password">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 text-sm font-medium" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 outline-none text-gray-900 @error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="Repeat password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-600 text-sm font-medium" />
        </div>

        <button type="submit" class="w-full flex justify-center py-3.5 px-4 mt-2 border border-transparent rounded-xl shadow-lg shadow-[#2D6A4F]/20 text-sm font-bold text-white bg-[#2D6A4F] hover:bg-[#1B4332] hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[#2D6A4F]/50 transition-all duration-200">
            Create Account
        </button>
        
        <p class="text-center text-sm font-medium text-gray-600 mt-6">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-[#2D6A4F] hover:text-[#1B4332] transition-colors">Sign In</a>
        </p>
    </form>
</x-guest-layout>
