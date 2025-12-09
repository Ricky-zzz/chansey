@extends('layouts.layout')

@section('content')
<div class="max-w-5xl mx-auto">
    

    <form action="{{ route('nurse.admitting.patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-8">
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-bold text-neutral">Edit Patient Profile</h2>
                </div>

                <fieldset class="mb-8">
                      <div class="divider text-lg font-bold text-neutral">BASIC INFORMATION</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>First Name</span>
                            <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full">
                            <span>Last Name</span>
                            <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full">
                            <span>Middle Name</span>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $patient->middle_name) }}" class="input input-md w-full" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Date of Birth</span>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" class="input input-md w-full" required />
                        </label>
                        
                        <label class="floating-label w-full">
                            <span>Sex</span>
                            <select name="sex" class="select select-bordered w-full">
                                <option value="Male" {{ $patient->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $patient->sex == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </label>

                        <label class="floating-label w-full">
                            <span>Civil Status</span>
                            <select name="civil_status" class="select select-bordered w-full">
                                @foreach(['Single', 'Married', 'Widowed', 'Separated'] as $status)
                                    <option value="{{ $status }}" {{ $patient->civil_status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </fieldset>

                <div class="divider text-lg font-bold text-neutral">CONTACT DETAILS</div>

                <fieldset class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Contact Number</span>
                            <input type="text" name="contact_number" value="{{ old('contact_number', $patient->contact_number) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full">
                            <span>Email Address</span>
                            <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="input input-md w-full" />
                        </label>
                        <label class="floating-label w-full md:col-span-2">
                            <span>Permanent Address</span>
                            <input type="text" name="address_permanent" value="{{ old('address_permanent', $patient->address_permanent) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full md:col-span-2">
                            <span>Present Address</span>
                            <input type="text" name="address_present" value="{{ old('address_present', $patient->address_present) }}" class="input input-md w-full" />
                        </label>
                    </div>
                </fieldset>

                <div class="divider text-lg font-bold text-neutral"> EMERGENCY CONTACT</div>

                <fieldset class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Contact Name</span>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full">
                            <span>Relationship</span>
                            <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full md:col-span-2">
                            <span>Contact Number</span>
                            <input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number', $patient->emergency_contact_number) }}" class="input input-md w-full" required />
                        </label>
                    </div>
                </fieldset>

                <div class="divider text-lg font-bold text-neutral"> IDENTIFICATION</div>

                <fieldset class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Nationality</span>
                            <input type="text" name="nationality" value="{{ old('nationality', $patient->nationality) }}" class="input input-md w-full" required />
                        </label>
                        <label class="floating-label w-full">
                            <span>Religion</span>
                            <input type="text" name="religion" value="{{ old('religion', $patient->religion) }}" class="input input-md w-full" />
                        </label>
                        <label class="floating-label w-full">
                            <span>PhilHealth #</span>
                            <input type="text" name="philhealth_number" value="{{ old('philhealth_number', $patient->philhealth_number) }}" class="input input-md w-full" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Senior Citizen ID</span>
                            <input type="text" name="senior_citizen_id" value="{{ old('senior_citizen_id', $patient->senior_citizen_id) }}" class="input input-md w-full" />
                        </label>
                    </div>
                </fieldset>

                <div class="flex justify-center items-center gap-4 mt-8">
                    <a href="{{ route('nurse.admitting.patients.show', $patient->id) }}" class="btn text-white bg-rose-500 btn-lg">Cancel</a>
                    <button type="submit" class="btn btn-primary  text-white btn-lg">Update Profile</button>
                </div>

            </div>
        </div>
    </form>

    @if($patient->admissions->count() === 0)
        <div class="card bg-base-100 shadow border border-error mt-8">
            <div class="card-body">
                <h3 class="font-bold text-error">Danger Zone</h3>
                <p class="text-sm">Deleting this patient will remove all their data permanently. This action cannot be undone.</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-error btn-sm text-white">Delete Patient</button>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection