<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Driver Assessments Ireland</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { navy: { DEFAULT: '#0b3168', dark: 'hsl(215,81%,14%)', light: 'hsl(215,81%,31%)' }, dai: { yellow: '#ffcf00' } } } } }</script>
</head>
<body class="bg-navy min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-8">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-navy rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-dai-yellow font-bold text-lg">DAI</span>
            </div>
            <h1 class="text-2xl font-bold text-navy">Admin Login</h1>
            <p class="text-gray-500 text-sm mt-1">Driver Assessments Ireland</p>
        </div>
        @if($errors->has('login'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg mb-5 text-sm">{{ $errors->first('login') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block font-semibold text-gray-700 mb-2 text-sm">Username</label>
                <input type="text" name="username" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-navy" value="{{ old('username') }}" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-2 text-sm">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-navy" required>
            </div>
            <button type="submit" class="w-full bg-navy text-white font-bold py-3 rounded-lg hover:bg-navy-light transition-colors">Login</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 text-sm hover:text-navy">← Back to website</a>
        </div>
    </div>
</body>
</html>
