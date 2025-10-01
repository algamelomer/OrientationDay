<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="relative min-h-screen w-full flex flex-col items-center justify-center p-4 bg-cover bg-center" style="background-image: url('{{ asset('images/background.jpg') }}');">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative z-10 w-full max-w-md mx-auto">
            <div class="bg-white/95 rounded-2xl shadow-2xl p-6 md:p-8">

                <div class="flex justify-around items-center w-full mb-4">
                <img src="{{ asset('images/Information-Technology-Department.png') }}" alt="IT logo" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mb-4 sm:mb-0">
                <img src="{{ asset(path: 'images/batu.png') }}" alt="BATU logo" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mt-4 sm:mt-0">
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-800">لوحة التحكم</h1>
                    <p class="text-gray-600">يرجى تسجيل الدخول للمتابعة</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2 text-right">
                            <i class="fas fa-envelope ml-2"></i>البريد الإلكتروني
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-2 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2 text-right">
                            <i class="fas fa-lock ml-2"></i>كلمة المرور
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-2 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember" class="mr-2 block text-sm text-gray-900">
                                تذكرني
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                                هل نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-transform transform hover:scale-105">
                            تسجيل الدخول
                        </button>
                    </div>
                </form>

                 <div class="mt-6 text-center">
                    <a href="{{ route('welcome') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                        <i class="fas fa-arrow-left ml-1"></i>
                        العودة للصفحة الرئيسية
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>