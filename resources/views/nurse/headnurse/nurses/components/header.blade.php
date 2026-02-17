{{-- Header Section --}}
<div class="card-enterprise p-5 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage shift schedules for nurses under your supervision</p>
        </div>
        <button @click="openBatchDtrModal()" class="btn-enterprise-secondary inline-flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Batch DTR Report
        </button>
    </div>
</div>
