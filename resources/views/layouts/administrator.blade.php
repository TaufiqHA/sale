<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-800 antialiased selection:bg-[#1e50d0] selection:text-white">

    <div class="flex h-full min-h-screen overflow-hidden">
        
        <!-- Sidebar component -->
        <x-sidebar />

        <!-- Main Body Wrapper -->
        <div class="flex flex-col flex-1 overflow-y-auto bg-white min-h-screen">
            
            <!-- Headbar component -->
            <x-headbar />

            <!-- Content Area (White background) -->
            <main class="flex-1 p-6 md:p-8 bg-white">
                @yield('content')
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
