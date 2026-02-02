<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book with Dr. {{ $doctor->last_name }} - Chansey Hospital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

    <!-- Flash Messages -->
    @if(session('error'))
    <div class="alert alert-error text-sm fixed bottom-6 right-6 w-96 shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="btn btn-sm btn-ghost">✕</button>
    </div>
    @endif

    <!-- HEADER -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        C
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Chansey Hospital</h1>
                </a>

                <!-- Back Button -->
                <a href="{{ route('public.doctors.index', $doctor->department_id) }}" class="btn btn-ghost btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Doctors
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="bookingApp()">
        
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('welcome') }}#appointment">Departments</a></li>
                <li><a href="{{ route('public.doctors.index', $doctor->department_id) }}">{{ $doctor->department->name }}</a></li>
                <li class="text-emerald-600 font-semibold">Dr. {{ $doctor->last_name }}</li>
            </ul>
        </div>

        <!-- Doctor Info Card -->
        <div class="card bg-white shadow-xl mb-8">
            <div class="card-body">
                <div class="flex items-center gap-6">
                    <div class="avatar placeholder">
                        <div class="bg-emerald-500 text-white rounded-full w-20 h-20 flex items-center justify-center">
                            <span class="text-3xl font-bold">
                                {{ strtoupper(substr($doctor->first_name, 0, 1) . substr($doctor->last_name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">
                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </h2>
                        <p class="text-emerald-600 font-medium">{{ $doctor->employment_type ?? 'Specialist' }}</p>
                        <p class="text-slate-500">{{ $doctor->department->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Slots -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-slate-800 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Available Appointment Slots
            </h3>

            @if($slots->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($slots as $slot)
                <div class="card bg-white shadow-md hover:shadow-lg transition-shadow border border-slate-100 cursor-pointer hover:border-emerald-300"
                     @click="openBookingModal({{ $slot->id }}, '{{ \Carbon\Carbon::parse($slot->date)->format('F d, Y') }}', '{{ \Carbon\Carbon::parse($slot->date)->format('l') }}', '{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}', '{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}', {{ $slot->capacity - $slot->appointments_count }})">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-lg text-slate-800">
                                    {{ \Carbon\Carbon::parse($slot->date)->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ \Carbon\Carbon::parse($slot->date)->format('l') }}
                                </p>
                                <p class="text-emerald-600 font-mono font-semibold mt-1">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="badge badge-success text-white">
                                    {{ $slot->capacity - $slot->appointments_count }} {{ Str::plural('slot', $slot->capacity - $slot->appointments_count) }} left
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-primary btn-sm gap-1">
                                        Book Now
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card bg-white shadow-md">
                <div class="card-body text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg text-gray-500 mb-4">No available slots at the moment.</p>
                    <p class="text-sm text-gray-400">Please check back later or choose another doctor.</p>
                    <a href="{{ route('public.doctors.index', $doctor->department_id) }}" class="btn btn-primary mt-4">
                        View Other Doctors
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- BOOKING MODAL -->
        <dialog id="booking_modal" class="modal" x-ref="bookingModal">
            <div class="modal-box max-w-lg">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                
                <h3 class="font-bold text-xl mb-2">Book Appointment</h3>
                
                <!-- Selected Slot Info -->
                <div class="bg-emerald-50 rounded-lg p-4 mb-6 border border-emerald-200">
                    <p class="text-sm text-slate-600">You are booking with:</p>
                    <p class="font-bold text-lg text-slate-800">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</p>
                    <div class="divider my-2"></div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Date:</span>
                        <span class="font-semibold text-slate-800" x-text="selectedDate + ' (' + selectedDay + ')'"></span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-slate-600">Time:</span>
                        <span class="font-semibold text-slate-800" x-text="selectedTime"></span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-slate-600">Remaining Slots:</span>
                        <span class="font-semibold text-emerald-600" x-text="remainingSlots"></span>
                    </div>
                </div>

                <form action="{{ route('public.appointment.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="appointment_slot_id" x-model="selectedSlotId">
                    
                    <!-- Name Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">First Name <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="first_name" placeholder="John" 
                                   class="input input-bordered" 
                                   value="{{ old('first_name') }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Last Name <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="last_name" placeholder="Doe" 
                                   class="input input-bordered" 
                                   value="{{ old('last_name') }}" required>
                        </div>
                    </div>
                    
                    <!-- Contact Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Mobile Number <span class="text-error">*</span></span>
                            </label>
                            <input type="tel" name="contact_number" placeholder="+63 912 345 6789" 
                                   class="input input-bordered" 
                                   value="{{ old('contact_number') }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Email</span>
                            </label>
                            <input type="email" name="email" placeholder="john@email.com" 
                                   class="input input-bordered"
                                   value="{{ old('email') }}">
                            <label class="label">
                                <span class="label-text-alt text-gray-400">For confirmation email</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Purpose -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Reason for Visit <span class="text-error">*</span></span>
                        </label>
                        <textarea name="purpose" class="textarea textarea-bordered h-24" 
                                  placeholder="Please briefly describe your symptoms or reason for the appointment..." 
                                  required>{{ old('purpose') }}</textarea>
                    </div>
                    
                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary w-full gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} Chansey Hospital. All rights reserved.</p>
        </div>
    </footer>

    <script>
    function bookingApp() {
        return {
            selectedSlotId: null,
            selectedDate: '',
            selectedDay: '',
            selectedTime: '',
            remainingSlots: 0,
            
            openBookingModal(slotId, date, day, startTime, endTime, remaining) {
                this.selectedSlotId = slotId;
                this.selectedDate = date;
                this.selectedDay = day;
                this.selectedTime = `${startTime} - ${endTime}`;
                this.remainingSlots = remaining;
                this.$refs.bookingModal.showModal();
            }
        }
    }
    </script>

</body>
</html>
