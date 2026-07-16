<!-- Headbar -->
<header class="sticky top-0 z-20 bg-white border-b border-slate-100 flex items-center justify-between h-20 px-6">
    <div class="flex items-center gap-3">
        <!-- Hamburger button for mobile & tablet (hidden on sm: ml-64 / desktop) -->
        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="text-slate-500 bg-transparent hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 font-medium rounded-lg text-sm p-2 focus:outline-none inline-flex sm:hidden cursor-pointer">
           <span class="sr-only">Open sidebar</span>
           <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
           </svg>
        </button>
        <!-- Page Title -->
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h1>
    </div>
</header>
