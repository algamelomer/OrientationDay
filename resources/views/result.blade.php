<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات الحضور</title>
    
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

        <div class="relative z-10 w-full max-w-lg mx-auto bg-white/95 rounded-2xl shadow-2xl p-6 md:p-8 transition-all duration-300">
            
            <div class="flex justify-around items-center w-full mb-4">
                <img src="{{ asset('images/Information-Technology-Department.png') }}" alt="Logo It" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mb-4 sm:mb-0">
                <img src="{{ asset(path: 'images/batu.png') }}" alt="Logo BATU" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mt-4 sm:mt-0">
            </div>

            <div class="text-center border-b-2 border-gray-200 pb-4 mb-6">
                <h1 class="text-3xl font-extrabold text-indigo-600">تهانينا! تم تأكيد تسجيلك</h1>
                <p class="mt-2 text-gray-600 text-lg">قسم تكنولوجيا المعلومات يرحب بك ويتمنى لك كل التوفيق.</p>
            </div>
            
            <div class="bg-blue-100 border-r-4 border-blue-500 text-blue-800 p-4 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-camera text-2xl"></i>
                <div>
                    <p class="font-bold">يرجى الاحتفاظ بهذه الصفحة كـ "سكرين شوت" لتتمكن من الدخول.</p>
                </div>
            </div>

            <div class="text-right space-y-4 text-xl mt-6">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <strong class="font-semibold text-gray-600 ml-2">الاسم:</strong>
                    <span class="text-gray-900 font-bold">{{ $student->name }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <strong class="font-semibold text-gray-600 ml-2">الرقم القومي:</strong>
                    <span class="text-gray-900 font-medium tracking-wider">{{ $student->national_id }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                    <strong class="font-semibold text-gray-600 ml-2">المكان:</strong>
                    <span class="text-indigo-700 font-bold text-2xl">{{ $student->place }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                    <strong class="font-semibold text-gray-600 ml-2">التوقيت:</strong>
                    <span class="text-indigo-700 font-bold text-2xl">{{ $timing }}</span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ $whatsapp_link }}" target="_blank" class="inline-block w-full bg-green-500 text-white font-bold py-4 px-6 rounded-xl hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300 transition-transform transform hover:scale-105 text-lg">
                    <i class="fab fa-whatsapp mr-2"></i>
                    هذا جروب الواتس الرسمي للدفعة
                </a>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('welcome') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                    <i class="fas fa-arrow-left ml-1"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>