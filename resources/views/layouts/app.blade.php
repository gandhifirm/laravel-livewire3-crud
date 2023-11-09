<!DOCTYPE html>
<html lang="en">
    <head>
        @vite([])
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Belajar Laravel Livewire</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @stack('style')
        @livewireStyles
    </head>

    <body>
        <div class="container py-5">
            {{ $slot }}
        </div>

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>

