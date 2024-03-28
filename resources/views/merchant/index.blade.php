<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Input Field</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex justify-center items-center h-screen bg-gradient-to-br from-purple-400 to-pink-600">
    
    <div>
        @error('merchant')
            <div class="alert alert-danger"> <p class="text-white"> {{ $message }} </p></div>
        @enderror
        @if (session()->has('error'))
            <div> <p class="text-red-700"> {{ session('error') }} </p></div>
        @endif
        <form action="{{route('merchant.create')}}" method="POST">
            @csrf
            @method('POST')
            <input type="text" name="merchant" class="w-full border border-purple-400 rounded-md py-2 px-4 focus:outline-none focus:ring focus:border-purple-500 bg-white text-gray-800">
        </form>
    </div>
</body>
</html>
