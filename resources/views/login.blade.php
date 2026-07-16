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

                <!-- Email Input Wrapper -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-white/80 pointer-events-none">
                        <!-- Envelope Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" required placeholder="EMAIL" 
                        class="w-full pl-12 pr-4 py-3 bg-transparent border border-[#4882fc] rounded-md text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent text-sm tracking-wider font-medium transition duration-200" />
                </div>

                <!-- Password Input Wrapper -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-white/80 pointer-events-none">
                        <!-- Lock Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" required placeholder="PASSWORD" 
                        class="w-full pl-12 pr-4 py-3 bg-transparent border border-[#4882fc] rounded-md text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent text-sm tracking-wider font-medium transition duration-200" />
                </div>

                <!-- Error Messages -->
                @error('email')
                <div class="text-red-200 text-xs text-center font-medium tracking-wide">
                    {{ $message }}
                </div>
                @enderror

                <!-- Login Button -->
                <button type="submit" 
                    class="w-full mt-2 py-3 bg-white text-[#1e50d0] font-bold rounded-md hover:bg-blue-50 active:scale-[0.99] transition-all duration-150 uppercase tracking-widest text-sm shadow-md cursor-pointer">
                    Login
                </button>
            </form>

            <!-- Forgot Password Link -->
            <div class="mt-4 text-center">
                <a href="#" class="text-xs text-white/70 hover:text-white transition duration-150 tracking-wider">
                    Forgot password?
                </a>
            </div>

        </div>
    </div>

</body>
</html>
