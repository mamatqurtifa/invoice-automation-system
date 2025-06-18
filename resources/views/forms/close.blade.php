<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }} - Form Closed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <h2 class="mt-2 text-xl font-bold text-gray-900">Form Closed</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $form->name }}</p>
            
            <div class="mt-4 bg-yellow-50 border border-yellow-100 rounded-md p-4">
                <p class="text-sm text-yellow-800">
                    This form is no longer accepting submissions as it has closed on {{ $form->closing_at->format('F j, Y \a\t g:i A') }}.
                </p>
            </div>
            
            <p class="mt-6 text-sm text-gray-500">
                Please contact the form owner if you have any questions.
            </p>
        </div>
    </div>
</body>
</html>