<!-- Sidebar for Mobile (Overlay & Panel) -->
<div id="mobile-sidebar" class="fixed inset-0 z-50 flex hidden lg:hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/80 transition-opacity ease-linear duration-300 opacity-0" onclick="toggleMobileSidebar()"></div>

    <!-- Drawer Panel -->
    <div id="mobile-sidebar-panel" class="relative flex w-full max-w-xs flex-1 transform transition ease-in-out duration-300 -translate-x-full bg-[#1e50d0] text-white flex-col rounded-r-2xl overflow-hidden">
        <!-- Logo & Close Button -->
        <div class="flex h-20 shrink-0 items-center justify-between px-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 stroke-white fill-none" viewBox="0 0 100 100" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M32 25 L43 57 L68 57 L74 37 L78 37" />
                    <circle cx="48" cy="65" r="3" />
                    <circle cx="63" cy="65" r="3" />
                    <path d="M56 31 L56 50" />
                    <path d="M50 37 L56 31 L62 37" />
                </svg>
                <span class="font-bold text-lg tracking-wider">SALE POS</span>
            </div>
            <button type="button" class="text-white/80 hover:text-white focus:outline-none" onclick="toggleMobileSidebar()">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1 px-4 py-4 overflow-y-auto">
            <a href="{{ route('administrator.dashboard') }}" class="group flex items-center gap-3 px-3 py-2 text-sm {{ request()->routeIs('administrator.dashboard') ? 'font-semibold bg-[#1641b3] text-white shadow-sm' : 'font-medium text-white/85 hover:bg-[#1641b3] hover:text-white' }} rounded-md transition-all">
                <svg class="h-5 w-5 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Dashboard
            </a>
            <a href="#" class="group flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md text-white/80 hover:bg-[#1641b3] hover:text-white transition-all">
                <svg class="h-5 w-5 text-white/75 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                Products
            </a>
            <a href="#" class="group flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md text-white/80 hover:bg-[#1641b3] hover:text-white transition-all">
                <svg class="h-5 w-5 text-white/75 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                </svg>
                Sales
            </a>
            <a href="{{ route('counters.index') }}" class="group flex items-center gap-3 px-3 py-2 text-sm {{ request()->routeIs('counters.*') ? 'font-semibold bg-[#1641b3] text-white shadow-sm' : 'font-medium text-white/85 hover:bg-[#1641b3] hover:text-white' }} rounded-md transition-all">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('counters.*') ? 'text-white' : 'text-white/75 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25M18.75 3H5.25A2.25 2.25 0 003 5.25" />
                </svg>
                Counters
            </a>
        </nav>

        <!-- User Profile & Logout Section at bottom of mobile sidebar -->
        <div class="p-4 border-t border-white/10 flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="truncate">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/60 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-white/10 hover:bg-white/20 active:scale-[0.98] rounded-md transition duration-150 cursor-pointer border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Sidebar for Desktop -->
<aside class="hidden lg:flex lg:w-64 lg:flex-col bg-[#1e50d0] text-white">
    <!-- Logo -->
    <div class="flex h-20 shrink-0 items-center px-6 border-b border-white/10 gap-3">
        <svg class="w-8 h-8 stroke-white fill-none" viewBox="0 0 100 100" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M32 25 L43 57 L68 57 L74 37 L78 37" />
            <circle cx="48" cy="65" r="3" />
            <circle cx="63" cy="65" r="3" />
            <path d="M56 31 L56 50" />
            <path d="M50 37 L56 31 L62 37" />
        </svg>
        <span class="font-bold text-lg tracking-wider uppercase">Sale POS</span>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 space-y-1 px-4 py-6">
        <a href="{{ route('administrator.dashboard') }}" class="group flex items-center gap-3 px-4 py-2.5 text-sm {{ request()->routeIs('administrator.dashboard') ? 'font-semibold bg-[#1641b3] text-white shadow-sm' : 'font-medium text-white/85 hover:bg-[#1641b3] hover:text-white' }} rounded-md transition-all">
            <svg class="h-5 w-5 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Dashboard
        </a>
        <a href="#" class="group flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-md text-white/85 hover:bg-[#1641b3] hover:text-white transition-all">
            <svg class="h-5 w-5 text-white/70 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            Products
        </a>
        <a href="#" class="group flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-md text-white/85 hover:bg-[#1641b3] hover:text-white transition-all">
            <svg class="h-5 w-5 text-white/70 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12a2.25 2.25 0 0020.25-18V6a2.25 2.25 0 00-18-2.25H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
            </svg>
            Sales
        </a>
        <a href="{{ route('counters.index') }}" class="group flex items-center gap-3 px-4 py-2.5 text-sm {{ request()->routeIs('counters.*') ? 'font-semibold bg-[#1641b3] text-white shadow-sm' : 'font-medium text-white/85 hover:bg-[#1641b3] hover:text-white' }} rounded-md transition-all">
            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('counters.*') ? 'text-white' : 'text-white/70 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25M18.75 3H5.25A2.25 2.25 0 003 5.25" />
            </svg>
            Counters
        </a>
    </nav>

    <!-- User Profile & Logout Section at bottom of desktop sidebar -->
    <div class="p-4 border-t border-white/10 flex flex-col gap-3">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="truncate">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-white/60 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-white bg-white/10 hover:bg-white/20 active:scale-[0.98] rounded-md transition duration-150 cursor-pointer border-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
