<!-- Headbar -->
<header class="sticky top-0 z-40 bg-white border-b border-slate-100 flex items-center justify-between h-20 px-6">
    <div class="flex items-center gap-3">
        <!-- Hamburger button for mobile -->
        <button type="button" class="text-slate-500 lg:hidden hover:text-slate-800 focus:outline-none p-2 -ml-2 rounded-lg hover:bg-slate-50 transition duration-150" onclick="toggleMobileSidebar()">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <!-- Page Title -->
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h1>
    </div>
</header>
