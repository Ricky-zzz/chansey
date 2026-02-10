<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Clock - Chansey Hospital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-white to-emerald-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
                    <img src="{{ asset('images/chansey.jpg') }}" alt="Chansey Logo" class="w-full h-full object-cover" />
                </div>
            </div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Chansey</h1>
            <p class="text-base text-slate-500">Time Clock System</p>
        </div>

        <!-- Messages -->
        @if ($errors->any())
            <div class="toast-enterprise-error flex items-start gap-3 mb-6 px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-6 w-6 mt-0.5" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2M9 1a8 8 0 018 8v10a1 1 0 01-1 1H4a1 1 0 01-1-1V9a8 8 0 018-8z" />
                </svg>
                <div>
                    <h3 class="font-bold text-sm">Error</h3>
                    <div class="text-sm">{{ $errors->first() }}</div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="toast-enterprise-success flex items-start gap-3 mb-6 px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-emerald-600 shrink-0 h-6 w-6 mt-0.5" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold text-sm">Success</h3>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card-enterprise">
            <div class="p-8">
                <h2 class="text-xl font-bold text-slate-800 mb-6">Clock In / Out</h2>

                <form method="POST" action="{{ route('dtr.store') }}" class="space-y-6">
                    @csrf

                    <!-- Badge ID -->
                    <div class="form-control">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Badge ID</label>
                        <input
                            type="text"
                            name="badge_id"
                            placeholder="e.g., NUR-001"
                            class="input-enterprise w-full py-3 text-base"
                            required
                            autofocus
                        />
                        @error('badge_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="input-enterprise w-full py-3 text-base"
                            required
                        />
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-control pt-4">
                        <div class="flex gap-4">
                            <!-- Time In Button -->
                            <button
                                type="submit"
                                name="action"
                                value="time_in"
                                class="btn-enterprise-primary flex-1 py-3 text-base font-bold inline-flex items-center justify-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0Z" />
                                </svg>
                                TIME IN
                            </button>

                            <!-- Time Out Button -->
                            <button
                                type="submit"
                                name="action"
                                value="time_out"
                                class="btn-enterprise-danger flex-1 py-3 text-base font-bold inline-flex items-center justify-center gap-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                                </svg>
                                TIME OUT
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="border-t border-slate-200 my-6"></div>
                <div class="text-center">
                    <p class="text-slate-600 text-sm mb-3">
                        <span class="font-bold text-slate-700">Current Time:</span>
                        <span id="clock" class="font-mono text-lg text-emerald-600 font-bold"></span>
                    </p>
                    <p class="text-xs text-slate-500">
                        If you need assistance, contact the Head Nurse
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update clock every second
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
