<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#1e50d0] text-white font-sans overflow-x-hidden antialiased selection:bg-white selection:text-[#1e50d0]">

    <!-- Background Layer (Fluid Waves & Blobs) -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none select-none">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" class="w-full h-full opacity-90">
            <!-- Base Gradient -->
            <rect width="1440" height="900" fill="#1e50d0" />

            <!-- Top Left Light Waves -->
            <path d="M -100,-100 C 200,-100 400,50 350,200 C 300,350 100,400 -100,300 Z" fill="#3a72f0" opacity="0.4" />
            <path d="M -100,-100 C 100,-100 250,0 220,100 C 190,200 50,250 -100,200 Z" fill="#4a85f7" opacity="0.5" />

            <!-- Left Bottom Dark Waves -->
            <path d="M -100,1000 C 200,1000 350,850 300,600 C 250,350 50,300 -100,300 Z" fill="#1641b3" opacity="0.5" />
            <path d="M -100,1000 C 100,1000 220,900 180,750 C 140,600 20,550 -100,550 Z" fill="#12379e" opacity="0.7" />

            <!-- Right Side Waves & Accent Blobs -->
            <path d="M 1540,1000 C 1200,1000 1100,800 1150,550 C 1200,300 1350,200 1540,200 Z" fill="#3a72f0" opacity="0.3" />
            <path d="M 1540,1000 C 1280,1000 1180,900 1220,700 C 1260,500 1380,400 1540,400 Z" fill="#1641b3" opacity="0.4" />

            <!-- Top Right Soft Light Wave -->
            <path d="M 1100,-100 C 1200,50 1350,-50 1540,50 V -100 Z" fill="#3a72f0" opacity="0.3" />
        </svg>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-10 flex min-h-full flex-col justify-center items-center px-6 py-12 sm:px-8">

        <!-- Inner Card/Form Area -->
        <div class="w-full max-w-[360px] flex flex-col items-center">

            <!-- Logo Section (Shopping Cart with Arrow and Wheels) -->
            <div class="mb-10 flex justify-center text-white">
                <svg class="w-24 h-24 stroke-white fill-none" viewBox="0 0 100 100" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                    <!-- Cart Outline -->
                    <path d="M32 25 L43 57 L68 57 L74 37 L78 37" />
                    <!-- Cart Wheels -->
                    <circle cx="48" cy="65" r="3" stroke-width="2.5" />
                    <circle cx="63" cy="65" r="3" stroke-width="2.5" />
                    <!-- Upward Arrow inside Cart -->
                    <path d="M56 31 L56 50" />
                    <path d="M50 37 L56 31 L62 37" />
                </svg>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="w-full flex flex-col gap-4">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block mb-2.5 text-sm font-medium text-white">Your email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="bg-transparent border border-[#4882fc] text-white text-sm rounded-md focus:ring-2 focus:ring-white/50 focus:border-transparent block w-full px-3 py-2.5 shadow-xs placeholder:text-white/60" placeholder="name@flowbite.com" required />
                    @error('email')
                        <p class="mt-2 text-xs text-red-200 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2.5 text-sm font-medium text-white">Your password</label>
                    <input type="password" name="password" id="password" class="bg-transparent border border-[#4882fc] text-white text-sm rounded-md focus:ring-2 focus:ring-white/50 focus:border-transparent block w-full px-3 py-2.5 shadow-xs placeholder:text-white/60" placeholder="••••••••" required />
                </div>
                {{-- <label for="remember" class="flex items-center mb-5 cursor-pointer">
                    <input id="remember" name="remember" type="checkbox" value="1" class="w-4 h-4 border border-[#4882fc] rounded-xs bg-transparent focus:ring-2 focus:ring-white/50 cursor-pointer" required />
                    <p class="ms-2 text-sm font-medium text-white select-none">I agree with the <a href="#" class="text-white hover:underline font-semibold">terms and conditions</a>.</p>
                </label> --}}
                <button type="submit" class="w-full text-[#1e50d0] bg-white box-border border border-transparent hover:bg-blue-50 focus:ring-4 focus:ring-white/50 shadow-xs font-semibold leading-5 rounded-md text-sm px-4 py-3 focus:outline-none cursor-pointer uppercase tracking-widest transition-all duration-150">Submit</button>
            </form>

            <!-- Forgot Password Link -->
            <div class="mt-6 text-center">
                <a href="#" class="text-xs text-white/70 hover:text-white transition duration-150 tracking-wider">
                    Forgot password?
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

</body>
</html>
