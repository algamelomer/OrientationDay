<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/Information-Technology-Department.png') }}" type="image/x-icon">
    <title>IT Department</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        #reader {
            width: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }
        #reader video {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="relative min-h-screen w-full flex flex-col items-center justify-center p-4 bg-cover bg-center" style="background-image: url('{{ asset('images/background.jpg') }}');">
        
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative z-10 w-full flex flex-col items-center justify-center text-center">
            
            <header class="w-full flex flex-col sm:flex-row items-center justify-around mb-8 px-4">
                <img src="{{ asset('images/Information-Technology-Department.png') }}" alt="IT logo" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white text-shadow-lg mx-4">
                    مرحبا بك في قسم تكنولوجيا المعلومات
                </h1>
                <img src="{{ asset(path: 'images/batu.png') }}" alt="BATU logo" class="h-20 w-20 md:h-24 md:w-24 object-contain p-1  mt-4 sm:mt-0">
            </header>

            <div class="w-full max-w-md bg-white/95 shadow-2xl rounded-2xl p-6 md:p-8">
                
                @if (session('error'))
                    <div class="bg-red-100 border-r-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-lg" role="alert">
                        <p class="font-bold">حدث خطأ!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                <h2 class="text-3xl font-bold text-gray-800 mb-4">تسجيل الحضور</h2>
                <p class="text-gray-600 mb-6">ادخل رقمك القومي المكون من 14 رقمًا أو استخدم الكاميرا لمسحه.</p>

                <form action="{{ route('check-in') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <input 
                            type="text" 
                            name="national_id" 
                            id="national_id_input" 
                            class="w-full px-4 py-3 text-lg text-center tracking-widest bg-gray-100 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                            placeholder="الرقم القومي" 
                            required 
                            pattern="\d{14}" 
                            title="يجب إدخال 14 رقمًا صحيحًا"
                            inputmode="numeric"
                        >
                        @error('national_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div id="reader" class="hidden my-4"></div>
                        <p id="scan-result" class="font-semibold"></p>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            {{-- <button type="button" id="scan-button" class="w-full flex items-center justify-center gap-2 bg-gray-700 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-transform transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>مسح الهوية</span>
                            </button> --}}
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-transform transform hover:scale-105">
                                تسجيل
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scanButton = document.getElementById('scan-button');
            const readerDiv = document.getElementById('reader');
            const resultP = document.getElementById('scan-result');
            const nationalIdInput = document.getElementById('national_id_input');
            let html5QrCode = null;

            function onScanSuccess(decodedText, decodedResult) {
                if (/^\d{14}$/.test(decodedText)) {
                    nationalIdInput.value = decodedText;
                    resultP.textContent = 'تم المسح بنجاح!';
                    resultP.classList.remove('text-red-600');
                    resultP.classList.add('text-green-600');
                    stopScanner();
                } else {
                    resultP.textContent = 'الكود الممسوح غير صالح. حاول مرة أخرى.';
                    resultP.classList.remove('text-green-600');
                    resultP.classList.add('text-red-600');
                }
            }
            
            function onScanFailure(error) { /* Ignore frequent errors */ }

            function stopScanner() {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        readerDiv.classList.add('hidden');
                        scanButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg> <span>مسح الهوية</span>`;
                    }).catch(err => console.error("Failed to stop the scanner.", err));
                }
            }

            scanButton.addEventListener('click', () => {
                if (html5QrCode && html5QrCode.isScanning) {
                    stopScanner();
                    return;
                }

                // **CHANGE 1: Initialize scanner with multi-format support**
                // This tells the scanner to look for different barcode types, not just QR codes.
                html5QrCode = new Html5Qrcode("reader", {
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.PDF_417,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                    ]
                }, /* verbose= */ false);
                
                readerDiv.classList.remove('hidden');
                resultP.textContent = '';
                
                // **CHANGE 2: Use a rectangular scanning box**
                const config = { 
                    fps: 10, 
                    qrbox: { width: 320, height: 120 } // Wide rectangle for ID cards
                };
                
                html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
                    .then(() => {
                       scanButton.textContent = 'إيقاف الكاميرا'; 
                    })
                    .catch(err => {
                        resultP.textContent = 'لا يمكن تشغيل الكاميرا.';
                        resultP.classList.add('text-red-600');
                        readerDiv.classList.add('hidden');
                    });
            });
        });
    </script>
</body>
</html>