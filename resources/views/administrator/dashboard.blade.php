<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-800 antialiased selection:bg-[#1e50d0] selection:text-white">

    <div class="flex h-full min-h-screen overflow-hidden">
        
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
                    <a href="#" class="group flex items-center gap-3 px-3 py-2 text-sm font-semibold rounded-md bg-[#1641b3] text-white">
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
                <a href="#" class="group flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-md bg-[#1641b3] text-white shadow-sm">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>
                    Sales
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

        <!-- Main Body Wrapper -->
        <div class="flex flex-col flex-1 overflow-y-auto bg-white min-h-screen">
            
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
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard</h1>
                </div>
            </header>

            <!-- Content Area (White background) -->
            <main class="flex-1 p-6 md:p-8 bg-white">
                
                <!-- Quick stats -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                    
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-[#1e50d0]/10 text-[#1e50d0] rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 font-medium">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-slate-800">Rp 12.450.000</h3>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 font-medium">Total Orders</p>
                            <h3 class="text-2xl font-bold text-slate-800">324</h3>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 font-medium">Active Members</p>
                            <h3 class="text-2xl font-bold text-slate-800">1,289</h3>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 font-medium">Sales Growth</p>
                            <h3 class="text-2xl font-bold text-slate-800">+12.3%</h3>
                        </div>
                    </div>

                </div>

                <!-- Recent Activities Section -->
                <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800">Recent Transactions</h2>
                        <a href="#" class="text-sm font-semibold text-[#1e50d0] hover:text-[#1641b3]">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-4">Transaction ID</th>
                                    <th class="px-6 py-4">Customer</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Amount</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94827</td>
                                    <td class="px-6 py-4">Budi Setiadi</td>
                                    <td class="px-6 py-4">16 Jul 2026</td>
                                    <td class="px-6 py-4 font-medium">Rp 450.000</td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Success</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94826</td>
                                    <td class="px-6 py-4">Siti Aminah</td>
                                    <td class="px-6 py-4">16 Jul 2026</td>
                                    <td class="px-6 py-4 font-medium">Rp 125.000</td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">Success</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-[#1e50d0]">#TRX-94825</td>
                                    <td class="px-6 py-4">Agus Prasetyo</td>
                                    <td class="px-6 py-4">15 Jul 2026</td>
                                    <td class="px-6 py-4 font-medium">Rp 1.200.000</td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>

    </div>

    <!-- Toggle Script for Mobile Sidebar Drawer -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            const panel = document.getElementById('mobile-sidebar-panel');
            
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.replace('opacity-0', 'opacity-100');
                    panel.classList.replace('-translate-x-full', 'translate-x-0');
                }, 20);
            } else {
                backdrop.classList.replace('opacity-100', 'opacity-0');
                panel.classList.replace('translate-x-0', '-translate-x-full');
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                }, 300);
            }
        }
    </script>

</body>
</html>
