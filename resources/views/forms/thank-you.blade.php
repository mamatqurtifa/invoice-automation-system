<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Thank You | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="max-w-md w-full mx-auto">
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Thank You!</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Your response has been submitted successfully.
                </p>

                @if(session('success'))
                    <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ route('forms.public', $form->id) }}" class="text-blue-600 hover:text-blue-800">
                        Submit another response
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>