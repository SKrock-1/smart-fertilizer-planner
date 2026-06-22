<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Smart Fertilizer Planner') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-serif {
                font-family: "DM Serif Display", serif;
            }
            .sfp-auth-bg {
                background-image: url('https://images.unsplash.com/photo-1592982537447-7440770cbfc9?q=80&w=2000&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
                position: relative;
            }
            .sfp-auth-bg::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(27, 67, 50, 0.9) 0%, rgba(27, 67, 50, 0.4) 100%);
            }
            
            /* Toast Animation */
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        
        <!-- Global Toast Notification System -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-full"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 translate-x-full"
                     class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('status') }}</span>
                    <button @click="show = false" class="ml-4 opacity-70 hover:opacity-100">&times;</button>
                </div>
            @endif
        </div>

        <div class="min-h-screen flex">
            <!-- Left Side: Image & Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 sfp-auth-bg p-12 flex-col justify-between items-start text-white overflow-hidden relative">
                <div class="relative z-10">
                    <a href="/" class="flex items-center gap-3 text-2xl font-serif tracking-tight">
                        <span class="flex items-center justify-center bg-[#d9ef6f] text-[#1B4332] w-12 h-12 rounded-xl -rotate-6 shadow-lg">🌱</span>
                        FertiPlan
                    </a>
                </div>
                
                <div class="relative z-10 max-w-lg mb-8">
                    <h1 class="text-5xl font-serif leading-tight mb-6 text-white drop-shadow-md">Precision agriculture starts with better data.</h1>
                    <p class="text-lg text-white/90 leading-relaxed">
                        Join thousands of farmers optimizing their yields and reducing fertilizer costs through data-driven agronomy.
                    </p>
                </div>
                
                <div class="relative z-10 text-sm text-white/70">
                    &copy; {{ date('Y') }} Smart Fertilizer Planner. All rights reserved.
                </div>
            </div>

            <!-- Right Side: Form Content -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 relative bg-white">
                
                <!-- Mobile Branding (Only visible on small screens) -->
                <div class="lg:hidden absolute top-8 left-8">
                    <a href="/" class="flex items-center gap-2 text-xl font-serif text-[#1B4332]">
                        <span class="flex items-center justify-center bg-[#d9ef6f] w-8 h-8 rounded-lg -rotate-6 shadow-sm">🌱</span>
                        FertiPlan
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
