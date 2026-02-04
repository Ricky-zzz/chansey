<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Chansey Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <style>
        @media print {
            body {
                background: white;
            }
            header, footer {
                display: none;
            }
            main {
                padding: 0 !important;
                max-width: 100% !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
            }
            .btn {
                display: none;
            }
            .text-center.mt-8 {
                display: none;
            }
            .bg-emerald-500,
            .bg-slate-50,
            .bg-blue-50,
            .bg-amber-50 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body class="bg-linear-to-br from-slate-50 to-blue-50 min-h-screen">

    <!-- HEADER -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-16">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        C
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Chansey Hospital</h1>
                </a>
            </div>
        </div>
    </header>

    <!-- SUCCESS CONTENT -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Booking Confirmed!</h1>
            <p class="text-slate-600">Your appointment has been successfully scheduled</p>
        </div>

        <!-- Appointment Details Card -->
        <div class="card bg-white shadow-xl">
            <div class="card-body">

                <!-- Reference Number -->
                <div class="text-center mb-6 pb-6 border-b border-slate-200">
                    <p class="text-sm text-slate-500">Reference Number</p>
                    <p class="text-2xl font-mono font-bold text-emerald-600">
                        APT-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <!-- Doctor Info -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="avatar placeholder">
                        <div class="bg-emerald-500 text-white rounded-full w-14 h-14 flex items-center justify-center">
                            <span class="text-xl font-bold">
                                {{ $appointment->appointmentSlot->physician->initials }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="font-bold text-lg text-slate-800">
                            Dr. {{ $appointment->appointmentSlot->physician->first_name }} {{ $appointment->appointmentSlot->physician->last_name }}
                        </p>
                        <p class="text-emerald-600">{{ $appointment->appointmentSlot->physician->department->name }}</p>
                    </div>
                </div>

                <!-- Appointment Details Grid -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Date</p>
                        <p class="font-bold text-slate-800">
                            {{ $appointment->formatted_date }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ $appointment->formatted_day }}
                        </p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Time</p>
                        <p class="font-bold text-slate-800 font-mono">
                            {{ $appointment->formatted_start_time }}
                        </p>
                        <p class="text-sm text-slate-500">
                            to {{ $appointment->formatted_end_time }}
                        </p>
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <p class="text-xs text-blue-600 uppercase tracking-wide mb-2 font-semibold">Patient Information</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-slate-500">Name:</span>
                            <span class="font-semibold text-slate-800">{{ $appointment->first_name }} {{ $appointment->last_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Contact:</span>
                            <span class="font-semibold text-slate-800">{{ $appointment->contact_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Email Confirmation Notice -->
                @if($appointment->email)
                <div class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">A confirmation email has been sent to <strong>{{ $appointment->email }}</strong></span>
                </div>
                @endif

                <!-- Reminders -->
                <div class="alert bg-amber-50 border-amber-200 mb-6">
                    <div>
                        <p class="font-semibold text-amber-800 mb-2"> Important Reminders</p>
                        <ul class="text-sm text-amber-700 list-disc list-inside space-y-1">
                            <li>Please arrive 15 minutes before your scheduled time</li>
                            <li>Bring a valid ID and any relevant medical records</li>
                            <li>If you need to cancel, please contact us at least 24 hours in advance</li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('welcome') }}" class="btn btn-primary flex-1 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Home
                    </a>
                    <button onclick="window.print()" class="btn btn-outline flex-1 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Details
                    </button>
                </div>

            </div>
        </div>

        <!-- Contact Info -->
        <div class="text-center mt-8 text-slate-500 text-sm">
            <p>Questions? Contact us at <strong class="text-slate-700">(555) 123-4567</strong> or <strong class="text-slate-700">contact@chansey.com</strong></p>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} Chansey Hospital. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
