<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <nav class="bg-slate-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <h1 class="text-xl font-bold">
                Interior Project Management
            </h1>

            <span class="text-sm text-gray-300">
                Admin / Project Manager
            </span>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-6 py-8">
        @yield('content')
    </main>

</body>
</html>