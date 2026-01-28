@extends('layouts.accountant')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ mode: 'view' }">

    <!-- BREADCRUMBS -->
    <div class="text-sm breadcrumbs mb-4">
        <ul>
            <li><a href="{{ route('accountant.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('accountant.billinginfo.index') }}">Billing Information</a></li>
            <li class="font-bold text-primary">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</li>
        </ul>
    </div>

    <!-- HEADER -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
        <div class="card-body p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Billing Information</h2>
                    <p class="text-sm text-gray-700 mt-2">
                        <span class="font-semibold">Patient:</span> {{ $admission->patient->getFullNameAttribute() }}
                        <span class="badge badge-neutral badge-sm ml-2">{{ $admission->admission_number }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="font-semibold">Admission Date:</span> {{ $admission->admission_date->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <!-- TOGGLE BUTTONS -->
                    <div class="join border border-slate-900  rounded-lg gap-1 p-0.5">
                        <button @click="mode = 'view'" :class="mode === 'view' ? 'bg-sky-500 text-white' : 'bg-gray-300 text-gray-700'" class="px-4 py-2 rounded join-item">View</button>
                        <button @click="mode = 'edit'" :class="mode === 'edit' ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-700'" class="px-4 py-2 rounded join-item">Edit</button>
                    </div>
                    <a href="{{ route('accountant.billinginfo.index') }}" class="btn btn-outline btn-error gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== VIEW MODE ========== -->
    <template x-if="mode === 'view'">
        <div>
            <!-- PATIENT & ADMISSION INFO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body p-6">
                        <h3 class="card-title text-primary border-b pb-2 mb-4">Patient Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Full Name</p>
                                <p class="text-gray-800 font-medium">{{ $admission->patient->getFullNameAttribute() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Sex / Age</p>
                                <p class="text-gray-800 font-medium">{{ $admission->patient->sex }} • {{ $admission->patient->age }} years old</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Admission Type</p>
                                <p class="text-gray-800 font-medium">{{ $admission->admission_type }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Admission Number</p>
                                <p class="text-gray-800 font-mono font-medium">{{ $admission->admission_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body p-6">
                        <h3 class="card-title text-primary border-b pb-2 mb-4">Admission Details</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Admission Date</p>
                                <p class="text-gray-800 font-medium">{{ $admission->admission_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Attending Physician</p>
                                <p class="text-gray-800 font-medium">{{ $admission->attendingPhysician->user->name ?? 'Not Assigned' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Chief Complaint</p>
                                <p class="text-gray-800 font-medium">{{ $admission->chief_complaint ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                                @php
                                    $statusColors = [
                                        'Admitted' => 'badge-primary',
                                        'Ready for Discharge' => 'badge-warning',
                                        'Discharged' => 'badge-success',
                                    ];
                                    $badgeClass = $statusColors[$admission->status] ?? 'badge-ghost';
                                @endphp
                                <div class="mt-1">
                                    <span class="badge {{ $badgeClass }}">{{ $admission->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BILLING INFO -->
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-6">
                    <h3 class="card-title text-primary border-b pb-2 mb-4">Billing & Insurance Information</h3>
                    
                    @if($billingInfo)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Payment Information -->
                            <div class="space-y-3">
                                <h4 class="font-bold text-gray-800 text-sm">Payment Information</h4>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Payment Type</p>
                                    @php
                                        $paymentColors = [
                                            'Cash' => 'badge-success',
                                            'Insurance' => 'badge-info',
                                            'HMO' => 'badge-warning',
                                            'Government' => 'badge-primary',
                                        ];
                                        $paymentBadgeClass = $paymentColors[$billingInfo->payment_type] ?? 'badge-ghost';
                                    @endphp
                                    <div class="mt-1">
                                        <span class="badge {{ $paymentBadgeClass }}">{{ $billingInfo->payment_type ?? 'Not Set' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Information -->
                            <div class="space-y-3">
                                <h4 class="font-bold text-gray-800 text-sm">Insurance Details</h4>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Primary Insurance Provider</p>
                                    <p class="text-gray-800 font-medium">{{ $billingInfo->primary_insurance_provider ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Policy Number</p>
                                    <p class="text-gray-800 font-mono font-medium">{{ $billingInfo->policy_number ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Approval Code</p>
                                    <p class="text-gray-800 font-mono font-medium">{{ $billingInfo->approval_code ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Guarantor Information -->
                        <div class="divider my-4"></div>
                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-800 text-sm">Guarantor Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Guarantor Name</p>
                                    <p class="text-gray-800 font-medium">{{ $billingInfo->guarantor_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Relationship</p>
                                    <p class="text-gray-800 font-medium">{{ $billingInfo->guarantor_relationship ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Contact Number</p>
                                    <p class="text-gray-800 font-mono font-medium">{{ $billingInfo->guarantor_contact ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance LOA Document -->
                        <div class="divider my-4"></div>
                        <div class="space-y-3">
                            <h4 class="font-bold text-gray-800 text-sm">Insurance Letter of Authorization (LOA)</h4>
                            @if($loaFile)
                                <div class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-green-700 font-semibold text-sm">{{ $loaFile->file_name }}</span>
                                    <a href="{{ route('document.view', $loaFile->id) }}" target="_blank" class="link link-primary text-sm ml-auto">View</a>
                                </div>
                            @else
                                <p class="text-gray-500 text-sm italic">No Insurance LOA document uploaded.</p>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>No billing information on file. Click "Edit" to add billing details.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </template>

    <!-- ========== EDIT MODE ========== -->
    <template x-if="mode === 'edit'">
        <form action="{{ route('accountant.billinginfo.update', $admission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-6">
                    <h3 class="card-title text-primary border-b pb-4 mb-6">Edit Billing & Insurance Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Payment Type -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Payment Type <span class="text-error">*</span></span>
                            </label>
                            <select name="payment_type" class="select select-bordered w-full" required>
                                <option value="">-- Select Payment Type --</option>
                                <option value="Cash" {{ $billingInfo && $billingInfo->payment_type === 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Insurance" {{ $billingInfo && $billingInfo->payment_type === 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                <option value="HMO" {{ $billingInfo && $billingInfo->payment_type === 'HMO' ? 'selected' : '' }}>HMO</option>
                                <option value="Government" {{ $billingInfo && $billingInfo->payment_type === 'Government' ? 'selected' : '' }}>Government</option>
                            </select>
                            @error('payment_type')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Primary Insurance Provider -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Primary Insurance Provider</span>
                            </label>
                            <input type="text" name="primary_insurance_provider" class="input input-bordered w-full" placeholder="e.g., PhilHealth, Medicard, etc." value="{{ old('primary_insurance_provider', $billingInfo?->primary_insurance_provider) }}">
                            @error('primary_insurance_provider')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Policy Number -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Policy Number</span>
                            </label>
                            <input type="text" name="policy_number" class="input input-bordered w-full" placeholder="Insurance policy number" value="{{ old('policy_number', $billingInfo?->policy_number) }}">
                            @error('policy_number')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Approval Code -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Approval Code</span>
                            </label>
                            <input type="text" name="approval_code" class="input input-bordered w-full" placeholder="Insurance approval/authorization code" value="{{ old('approval_code', $billingInfo?->approval_code) }}">
                            @error('approval_code')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <!-- Guarantor Section -->
                    <div class="divider my-4">Guarantor Information</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Guarantor Name -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Guarantor Name</span>
                            </label>
                            <input type="text" name="guarantor_name" class="input input-bordered w-full" placeholder="Full name of guarantor/next of kin" value="{{ old('guarantor_name', $billingInfo?->guarantor_name) }}">
                            @error('guarantor_name')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Guarantor Relationship -->
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Relationship to Patient</span>
                            </label>
                            <input type="text" name="guarantor_relationship" class="input input-bordered w-full" placeholder="e.g., Spouse, Parent, Child, etc." value="{{ old('guarantor_relationship', $billingInfo?->guarantor_relationship) }}">
                            @error('guarantor_relationship')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Guarantor Contact -->
                        <div class="form-control w-full md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Contact Number</span>
                            </label>
                            <input type="tel" name="guarantor_contact" class="input input-bordered w-full" placeholder="Phone number" value="{{ old('guarantor_contact', $billingInfo?->guarantor_contact) }}">
                            @error('guarantor_contact')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <!-- Insurance LOA Document Upload -->
                    <div class="divider my-4">Insurance LOA</div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Insurance Letter of Authorization (LOA)</span>
                        </label>
                        
                        @if($loaFile)
                        <div class="flex items-center gap-2 mb-3 p-3 bg-green-50 border border-green-200 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-green-700 font-semibold text-sm">{{ $loaFile->file_name }}</span>
                            <a href="{{ route('document.view', $loaFile->id) }}" target="_blank" class="link link-primary text-sm ml-auto">View</a>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Upload a new file to replace the current document.</p>
                        @endif

                        <input type="file" name="doc_loa" class="file-input file-input-bordered file-input-md w-full" accept=".pdf,.jpg,.png,.jpeg">
                        @error('doc_loa')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="flex justify-end gap-2 mt-6 pb-12">
                <button type="button" @click="mode = 'view'" class="btn btn-error">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary text-white gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Billing Information
                </button>
            </div>

        </form>
    </template>

</div>
@endsection
