<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umico Merchant</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-br from-purple-400 to-pink-600">
    <div class="w-1/3">
        @error('merchant')
            <div class="alert alert-danger"> <p class="text-white"> {{ $message }} </p></div>
        @enderror
        @if (session()->has('error'))
            <div> <p class="text-red-700"> {{ session('error') }} </p></div>
        @endif
        <form action="{{route('merchant.create')}}" method="POST">
            @csrf
            @method('POST')
            <input placeholder="Your merchant name here..." type="text" name="merchant" class="w-full px-4 py-2 text-gray-800 bg-white border border-purple-400 rounded-md focus:outline-none focus:ring focus:border-purple-500">
        </form>
    </div>
</body>
</html>
