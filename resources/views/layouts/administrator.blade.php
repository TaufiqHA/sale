<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F6FC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-800 antialiased selection:bg-brand selection:text-white">

    <div class="min-h-screen bg-[#F4F6FC]">

        <!-- Sidebar component -->
        <x-sidebar />

        <!-- Main Body Wrapper -->
        <div class="sm:ml-64 flex flex-col min-h-screen bg-[#F4F6FC]">

            <!-- Headbar component -->
            <x-headbar />

            <!-- Content Area (Ice blue background) -->
            <main class="flex-1 p-6 md:p-8 bg-[#F4F6FC]">
                @yield('content')
            </main>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

</body>
</html>
