<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Clock - Chansey Hospital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 to-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <div class="avatar">
                    <div class="w-20 rounded-full ring ring-primary ring-offset-slate-900 ring-offset-2">
                        <img src="{{ asset('images/chansey.jpg') }}" alt="Chansey Logo" />
                    </div>
                </div>
            </div>
            <h1 class="text-4xl font-black text-white mb-2">Chansey</h1>
            <p class="text-lg text-slate-300">Time Clock System</p>
        </div>

        <!-- Messages -->
        @if ($errors->any())
            <div class="alert alert-error shadow-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2M9 1a8 8 0 018 8v10a1 1 0 01-1 1H4a1 1 0 01-1-1V9a8 8 0 018-8z" />
                </svg>
                <div>
                    <h3 class="font-bold">Error</h3>
                    <div class="text-sm">{{ $errors->first() }}</div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success shadow-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">Success</h3>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card bg-slate-800 shadow-2xl border border-slate-700">
            <div class="card-body">
                <h2 class="card-title text-white text-2xl mb-6">Clock In / Out</h2>

                <form method="POST" action="{{ route('dtr.store') }}" class="space-y-6">
                    @csrf

                    <!-- Badge ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-slate-300 font-semibold">Badge ID</span>
                        </label>
                        <input
                            type="text"
                            name="badge_id"
                            placeholder="e.g., NUR-001"
                            class="input input-bordered input-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            required
                            autofocus
                        />
                        @error('badge_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-slate-300 font-semibold">Password</span>
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="input input-bordered input-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            required
                        />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
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
                                class="btn btn-success btn-lg flex-1 text-white font-bold text-lg gap-2 hover:btn-success"
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
                                class="btn btn-error btn-lg flex-1 text-white font-bold text-lg gap-2 hover:btn-error"
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
                <div class="divider my-6"></div>
                <div class="text-center">
                    <p class="text-slate-400 text-sm mb-3">
                        <span class="font-bold text-slate-300">Current Time:</span>
                        <span id="clock" class="font-mono text-lg text-primary font-bold"></span>
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
