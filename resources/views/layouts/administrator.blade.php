<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-800 antialiased selection:bg-[#1e50d0] selection:text-white">

    <div class="min-h-screen bg-slate-50">

        <!-- Sidebar component -->
        <x-sidebar />

        <!-- Main Body Wrapper -->
        <div class="sm:ml-64 flex flex-col min-h-screen bg-white">

            <!-- Headbar component -->
            <x-headbar />

            <!-- Content Area (White background) -->
            <main class="flex-1 p-6 md:p-8 bg-white">
                @yield('content')
            </main>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

</body>
</html>
