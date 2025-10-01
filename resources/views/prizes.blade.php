@extends('layouts.app')

@section('content')
    {{-- We'll add some style directly for the background and custom animations --}}
    <style>
        body {
            background-color: #0f172a;
            /* A dark blue-gray fallback */
        }

        .card-glow {
            box-shadow: 0 0 25px rgba(79, 70, 229, 0.5), 0 0 10px rgba(167, 139, 250, 0.4);
        }

        .winner-glow {
            animation: winner-glow-animation 2s infinite alternate;
        }

        @keyframes winner-glow-animation {
            from {
                box-shadow: 0 0 30px rgba(22, 163, 74, 0.6), 0 0 15px rgba(74, 222, 128, 0.5);
            }

            to {
                box-shadow: 0 0 45px rgba(22, 163, 74, 1), 0 0 25px rgba(74, 222, 128, 0.8);
            }
        }

        .prize-enter {
            transition: transform 1s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s;
        }
    </style>

    <div
        class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-indigo-900 min-h-screen text-white font-sans p-4 flex flex-col items-center justify-center relative overflow-hidden">

        {{-- Header --}}
        <header class="absolute top-0 left-0 right-0 p-6 z-20">
            <nav class="container mx-auto flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-wider text-white">سحب الجوائز</h1>
                {{-- <div>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-300 hover:text-white transition-colors duration-300 mx-3">لوحة التحكم</a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="text-gray-300 hover:text-white transition-colors duration-300 mx-3">
                        تسجيل الخروج
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div> --}}
            </nav>
        </header>

        {{-- Prize images for final animation (initially off-screen) --}}
        {{-- Prize icons for final animation (initially off-screen) --}}
        <div id="prize-left"
            class="prize-enter absolute top-1/2 -translate-y-1/2 left-0 opacity-0 transform -translate-x-full z-30 text-8xl text-amber-300 drop-shadow-[0_0_20px_rgba(253,230,138,0.8)]">
            <i class="fas fa-bullhorn text-6xl text-yellow-400 drop-shadow-[0_0_10px_rgba(252,211,77,0.8)]"></i>
        </div>
        <div id="prize-right"
            class="prize-enter absolute top-1/2 -translate-y-1/2 right-0 opacity-0 transform translate-x-full z-30 text-8xl text-amber-300 drop-shadow-[0_0_20px_rgba(253,230,138,0.8)] scale-x-[-1]">
            <i class="fas fa-bullhorn text-6xl text-yellow-400 drop-shadow-[0_0_10px_rgba(252,211,77,0.8)]"></i>
        </div>


        <main id="main-content" class="container mx-auto flex-grow flex items-center justify-center">
            <div id="setup-area"
                class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-lg p-8 max-w-2xl w-full card-glow">
                <h2 class="text-3xl font-semibold mb-8 text-white border-b-2 border-white/20 pb-4 text-center">اختر قاعة
                    للسحب</h2>

                <div class="mb-8">
                    <select id="hall-selector"
                        class="block w-full bg-white/10 border-2 border-white/30 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xl p-4 text-white">
                        <option value="" class="text-black">-- اختر قاعة --</option>
                        @foreach ($halls as $hall)
                            <option value="{{ $hall->name }}" class="text-black">{{ $hall->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <button id="draw-button"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 px-12 rounded-lg hover:from-purple-700 hover:to-indigo-700 text-2xl font-bold transition-all duration-300 transform hover:scale-110 disabled:bg-gray-600 disabled:from-gray-600 disabled:cursor-not-allowed disabled:scale-100"
                        disabled>
                        <i class="fas fa-dice mr-3"></i> اسحب الآن!
                    </button>
                </div>
            </div>

            {{-- Area for the drawing animation --}}
            <div id="drawing-area" class="text-center hidden">
                <h3 class="text-4xl font-bold text-gray-300 mb-6 animate-pulse">جاري اختيار الفائز...</h3>
                <div id="names-roulette"
                    class="text-7xl font-mono font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-300 via-cyan-400 to-purple-400 h-24 flex items-center justify-center transition-all duration-300"
                    style="text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);">
                </div>
            </div>

            {{-- Area for the final result --}}
            <div id="result-area" class="text-center hidden">
                <h3 class="text-3xl font-bold text-gray-200 mb-4">🏆 الفائز هو... 🏆</h3>
                <div
                    class="bg-green-500/20 border-2 border-green-400 text-white p-8 rounded-2xl shadow-lg relative winner-glow">
                    <p id="winner-name" class="text-6xl font-extrabold" style="text-shadow: 0 0 20px #fff;"></p>
                    <p id="winner-nid" class="text-3xl text-gray-300 mt-3"></p>
                </div>
                <button id="draw-again-button"
                    class="mt-8 bg-gradient-to-r from-pink-500 to-orange-500 text-white py-3 px-8 rounded-lg hover:from-pink-600 hover:to-orange-600 text-xl font-bold transition-all duration-300 transform hover:scale-110">
                    <i class="fas fa-redo-alt mr-2"></i> سحب مرة أخرى
                </button>
            </div>

            <div id="error-area" class="mt-8 text-center hidden">
                <div class="bg-red-500/20 border-2 border-red-500 text-white p-6 rounded-lg shadow-lg">
                    <p id="error-message" class="text-2xl"></p>
                </div>
            </div>
        </main>
    </div>

    {{-- Confetti library for the celebration --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    {{-- Confetti library for the celebration --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- DOM Elements ---
            const hallSelector = document.getElementById('hall-selector');
            const drawButton = document.getElementById('draw-button');
            const drawAgainButton = document.getElementById('draw-again-button');

            const setupArea = document.getElementById('setup-area');
            const drawingArea = document.getElementById('drawing-area');
            const resultArea = document.getElementById('result-area');
            const errorArea = document.getElementById('error-area');

            const namesRoulette = document.getElementById('names-roulette');
            const winnerNameEl = document.getElementById('winner-name');
            const winnerNidEl = document.getElementById('winner-nid');
            const errorMessageEl = document.getElementById('error-message');

            const prizeLeft = document.getElementById('prize-left');
            const prizeRight = document.getElementById('prize-right');

            // --- Sample names for animation ---
            const sampleNames = ["أحمد علي", "فاطمة محمد", "يوسف خالد", "مريم حسين", "عبدالله سعيد", "نور مصطفى",
                "عمر جمال", "سارة إبراهيم", "خالد وليد", "هنا طارق"
            ];

            // --- Event Listeners ---
            hallSelector.addEventListener('change', () => {
                drawButton.disabled = !hallSelector.value;
            });

            drawButton.addEventListener('click', startDraw);
            drawAgainButton.addEventListener('click', resetDraw);

            // --- Functions ---
            function startDraw() {
                const hallName = hallSelector.value;
                if (!hallName) return;

                // 1. Transition UI to drawing state
                setupArea.classList.add('hidden');
                resultArea.classList.add('hidden');
                errorArea.classList.add('hidden');
                drawingArea.classList.remove('hidden');

                // 2. Start the 5-second name roulette animation
                let duration = 5000;
                let startTime = Date.now();

                // This is the recursive function for the animation
                function roulette() {
                    const elapsed = Date.now() - startTime;

                    // Continue the animation as long as we are within the 5-second duration
                    if (elapsed < duration) {
                        // Pick a random name and display it
                        const randomIndex = Math.floor(Math.random() * sampleNames.length);
                        namesRoulette.textContent = sampleNames[randomIndex];

                        // **CHANGE 1: Increased blur and kept it constant**
                        // The names are now much blurrier and stay blurry for the whole animation.
                        namesRoulette.style.filter = 'blur(8px)';

                        // Gradually slow down the name cycling speed
                        let speed = 50; // Initial fast speed
                        if (elapsed > duration * 0.6) speed = 150; // Slow down
                        if (elapsed > duration * 0.85) speed = 350; // Slow down more

                        // Call the next frame of the animation
                        setTimeout(roulette, speed);
                    } else {
                        // **CHANGE 2: Clean hand-off to fetch the winner**
                        // Once the 5 seconds are up, the animation stops and we immediately
                        // call the function to get the real winner. This fixes the bug.
                        fetchWinner(hallName);
                    }
                }

                roulette(); // Start the animation cycle
            }

            function fetchWinner(hallName) {
                // Show a clear placeholder for suspense AFTER the blurry animation is done.
                namesRoulette.textContent = '...';
                namesRoulette.style.filter = 'blur(0px)';

                fetch('{{ route('prizes.draw') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            hall_name: hallName
                        })
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => {
                            throw err;
                        });
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            showError(data.error);
                        } else {
                            // Wait a moment before revealing the winner for dramatic effect
                            setTimeout(() => showWinner(data), 500);
                        }
                    })
                    .catch(error => {
                        showError(error.error || 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
                    });
            }

            function showWinner(winner) {
                drawingArea.classList.add('hidden');

                // Populate winner data
                winnerNameEl.textContent = winner.name;
                winnerNidEl.textContent = 'الرقم القومي: ' + winner.national_id;

                resultArea.classList.remove('hidden');

                // Trigger celebration animations!
                celebrate();
            }

            function showError(message) {
                drawingArea.classList.add('hidden');
                errorMessageEl.textContent = message;
                errorArea.classList.remove('hidden');
                drawAgainButton.classList.remove('hidden');
                resultArea.appendChild(drawAgainButton);
            }

            function celebrate() {
                // Confetti from two sides
                confetti({
                    particleCount: 150,
                    angle: 60,
                    spread: 55,
                    origin: {
                        x: 0
                    }
                });
                confetti({
                    particleCount: 150,
                    angle: 120,
                    spread: 55,
                    origin: {
                        x: 1
                    }
                });

                // Slide in prizes
                setTimeout(() => {
                    prizeLeft.classList.remove('opacity-0', '-translate-x-full');
                    prizeRight.classList.remove('opacity-0', 'translate-x-full');
                }, 300);
            }

            function resetDraw() {
                // Hide results and prizes
                resultArea.classList.add('hidden');
                errorArea.classList.add('hidden');
                prizeLeft.classList.add('opacity-0', '-translate-x-full');
                prizeRight.classList.add('opacity-0', 'translate-x-full');

                // Reset the form
                hallSelector.value = '';
                drawButton.disabled = true;
                setupArea.classList.remove('hidden');
            }
        });
    </script>
@endsection
