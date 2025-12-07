@extends('layouts.layout')

@section('content')
    <!-- 1. THE SEARCH HERO -->
    <!-- Used bg-base-100 (White) with a strong shadow to pop against the gray page background -->
    <div class="card bg-base-100 shadow-xl mb-10 border border-base-200">
        <div class="card-body p-10 text-center">
            <h1 class="text-4xl font-black text-slate-800 mb-2">Patient Admission</h1>
            <p class="text-slate-500 mb-8">Process new walk-ins, emergency arrivals, or scheduled procedures.</p>
            
            <div class="flex flex-col md:flex-row gap-2 max-w-3xl mx-auto w-full">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" class="input input-bordered input-lg w-full pl-12 focus:input-primary" placeholder="Scan QR or Search (Last Name, PID, DOB)..." />
                </div>
                <button class="btn btn-primary btn-lg text-white px-10 shadow-lg shadow-cyan-500/50">Search</button>
            </div>
            
            <div class="mt-4 text-sm font-medium">
                Patient not found? 
                <a href="{{ route('nurse.admitting.patients.create') }}" class="link link-secondary font-bold hover:text-secondary-focus transition-colors">Register New Patient</a>
            </div>
        </div>
    </div>

    <!-- 2. BED AVAILABILITY CARDS -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-slate-800">Bed Status Overview</h2>
        <button class="btn btn-ghost btn-sm text-primary">View All Rooms &rarr;</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Private Rooms (Active/Good) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-primary hover:-translate-y-1 transition-transform">
            <div class="card-body p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wide">Private Rooms</div>
                        <div class="text-3xl font-black text-slate-800 mt-1">3 <span class="text-lg font-normal text-gray-400">/ 8</span></div>
                    </div>
                    <div class="p-3 bg-cyan-50 rounded-lg text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2m7-2a2 2 0 11-4 0v2m-5-2a2 2 0 11-4 0v2" /></svg>
                    </div>
                </div>
                <div class="badge badge-success badge-sm gap-1 mt-3 text-white">
                    Available
                </div>
            </div>
        </div>

        <!-- Male Ward (Warning) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-warning hover:-translate-y-1 transition-transform">
            <div class="card-body p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wide">Male Ward</div>
                        <div class="text-3xl font-black text-slate-800 mt-1">1 <span class="text-lg font-normal text-gray-400">/ 20</span></div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <div class="badge badge-warning badge-sm gap-1 mt-3">
                    High Occupancy
                </div>
            </div>
        </div>
        
        <!-- ICU (Critical) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-error hover:-translate-y-1 transition-transform">
            <div class="card-body p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wide">ICU</div>
                        <div class="text-3xl font-black text-slate-800 mt-1">0 <span class="text-lg font-normal text-gray-400">/ 5</span></div>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                </div>
                <div class="badge badge-error text-white badge-sm gap-1 mt-3">
                    Full Capacity
                </div>
            </div>
        </div>

    </div>

    <!-- 3. RECENT ACTIVITY TABLE -->
    <h2 class="text-2xl font-bold text-slate-800 mb-4">Recent Admissions</h2>
    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra table-lg">
                <!-- head -->
                <thead class="bg-base-200 text-slate-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="rounded-tl-lg">Patient Info</th>
                        <th>Admission Type</th>
                        <th>Time</th>
                        <th>Assigned Bed</th>
                        <th class="rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr class="hover">
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-10">
                                        <span class="text-xs">JD</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Juan Dela Cruz</div>
                                    <div class="text-xs opacity-50 font-mono">P-2025-001</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="badge badge-outline badge-primary font-bold">Inpatient</div>
                        </td>
                        <td class="text-sm font-medium">10 mins ago</td>
                        <td>
                            <span class="font-bold text-slate-700">Ward 3-A</span>
                            <br/>
                            <span class="text-[10px] text-success">Cleaning Done</span>
                        </td>
                        <th>
                            <button class="btn btn-ghost btn-xs">Details</button>
                        </th>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover">
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar placeholder">
                                    <div class="bg-secondary text-secondary-content rounded-full w-10">
                                        <span class="text-xs">MC</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Maria Clara</div>
                                    <div class="text-xs opacity-50 font-mono">P-2025-002</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="badge badge-outline badge-error font-bold">Emergency</div>
                        </td>
                        <td class="text-sm font-medium">1 hour ago</td>
                        <td>
                            <span class="font-bold text-slate-700">ER - Bed 2</span>
                            <br/>
                            <span class="text-[10px] text-warning">Waitlisted</span>
                        </td>
                        <th>
                            <button class="btn btn-ghost btn-xs">Details</button>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection