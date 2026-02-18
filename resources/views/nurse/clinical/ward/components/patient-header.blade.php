<!-- Header: Patient Context -->
<div class="card-enterprise p-5 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
        <div class="flex items-center gap-4">
            <div class="avatar placeholder">
                <div class="bg-emerald-100 text-emerald-700 rounded-lg w-14 h-14 flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ substr($admission->patient->first_name, 0, 1) }}</span>
                </div>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">
                    {{ $admission->patient->getFullNameAttribute() }}
                    @if($admission->status !== 'Admitted')
                        <span class="ml-2 inline-block px-3 py-0.5 bg-red-50 text-red-700 rounded-md text-xs font-semibold border border-red-200">
                            {{ $admission->status}}
                        </span>
                    @endif
                </h1>
                <div class="flex gap-2 items-center mt-1 text-sm text-slate-500">
                    <span class="font-medium text-slate-600">{{ $admission->patient->age }}yo / {{ $admission->patient->sex }}
                    &middot; Admitted: {{ $admission->admission_date->format('M d') }}</span>
                </div>
                <div class="flex gap-2 items-center mt-1 text-sm text-slate-500">
                    Room: <span class="font-semibold text-slate-700">{{ $admission->bed->bed_code ?? "Outpatient" }}</span>
                </div>
                <!-- Allergies Alert -->
                @if(!empty($admission->known_allergies))
                <div class="mt-2 flex gap-1 items-center">
                    <span class="text-xs font-semibold text-slate-500">Allergies:</span>
                    @foreach($admission->known_allergies as $allergy)
                    <span class="badge badge-error text-white badge-xs">{{ $allergy }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 items-end">
            <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Actions</h2>
            <div class="flex flex-row gap-2 items-center">
                <button onclick="supply_modal.showModal()" class="btn-enterprise-warning gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Charge Item
                </button>
                <!-- SPONTANEOUS LOG BUTTON -->
                <button @click="openLogModal(null, null)" class="btn-enterprise-info gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Add Clinical Note
                </button>
                <!-- NURSING CARE PLAN -->
                <a href="{{ route('nurse.clinical.care-plan.edit', $admission->id) }}" class="btn-enterprise-primary gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    Care Plan
                </a>
                <!-- CREATE ENDORSEMENT -->
                <a href="{{ route('nurse.clinical.endorsments.create', $admission->id) }}" class="btn-enterprise-primary gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6-6m0 0l6 6m-6-6v12a6 6 0 01-12 0v-3.5m12 3.5v3.5a6 6 0 01-12 0v-3.5m0-9.5h.008v.008H3V9zm16.5 0h.008v.008h-.008V9z" />
                    </svg>
                    Create Endorsement
                </a>
            </div>
        </div>
    </div>
</div>
