<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $department->name }} Specialists - Golden Gate Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

    <!-- HEADER -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        C
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Golden Gate Academy</h1>
                </a>

                <!-- Back Button -->
                <a href="{{ route('welcome') }}#appointment" class="btn btn-ghost btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Departments
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('welcome') }}#appointment">Departments</a></li>
                <li class="text-emerald-600 font-semibold">{{ $department->name }}</li>
            </ul>
        </div>

        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-slate-900 mb-4">{{ $department->name }} Specialists</h2>
            <p class="text-lg text-slate-600">Choose a doctor to view their available appointment slots</p>
        </div>

        <!-- Doctors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($doctors as $doctor)
            <div class="card bg-white shadow-xl hover:shadow-2xl transition-shadow duration-300 border border-slate-100">
                <div class="card-body">
                    <!-- Doctor Avatar -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="avatar placeholder">
                            <div class="bg-emerald-500 text-white rounded-full w-16 h-16 flex items-center justify-center">
                                <span class="text-2xl font-bold">
                                    {{ strtoupper(substr($doctor->first_name, 0, 1) . substr($doctor->last_name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">
                                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                            </h3>
                            <p class="text-sm text-emerald-600 font-medium">{{ $doctor->employment_type ?? 'Specialist' }}</p>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>{{ $department->name }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            @if($doctor->available_slots_count > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-emerald-600 font-medium">{{ $doctor->available_slots_count }} upcoming {{ Str::plural('slot', $doctor->available_slots_count) }}</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-400">No available slots</span>
                            @endif
                        </div>
                    </div>

                    <!-- Book Button -->
                    <div class="card-actions">
                        @if($doctor->available_slots_count > 0)
                            <a href="{{ route('public.doctors.book', $doctor->id) }}"
                               class="btn btn-primary w-full gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Book Appointment
                            </a>
                        @else
                            <button class="btn btn-disabled w-full" disabled>
                                No Available Slots
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-lg text-gray-400">No doctors available in this department.</p>
                <a href="{{ route('welcome') }}#appointment" class="btn btn-primary mt-4">
                    Choose Another Department
                </a>
            </div>
            @endforelse
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} Golden Gate Academy. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
