@extends('layouts.accountant')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    editMode: false,
    editId: null,
    editName: '',
    editPrice: '',
    editUnit: '',
    editAction: '',
    editIsActive: false,

    openEdit(fee) {
        this.editMode = true;
        this.editId = fee.id;
        this.editName = fee.name;
        this.editPrice = fee.price;
        this.editUnit = fee.unit;
        this.editIsActive = fee.is_active ? true : false;
        this.editAction = '/accountant/fees/' + fee.id; // Ensure this matches route pattern
        document.getElementById('fee_modal').showModal();
    },

    openCreate() {
        this.editMode = false;
        this.editName = '';
        this.editPrice = '';
        this.editUnit = 'per_use';
        this.editIsActive = true;
        document.getElementById('fee_modal').showModal();
    }
}">

    <div class="card-enterprise p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Hospital Fees</h2>
                <p class="text-sm text-slate-500">Manage standard charges for billing.</p>
            </div>

            <div class="flex gap-2">
                <form action="{{ route('accountant.fees.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" class="input-enterprise text-sm" placeholder="Search Fee..." value="{{ request('search') }}">
                    <button class="btn-enterprise-primary text-sm">Search</button>
                </form>
                <button @click="openCreate()" class="btn-enterprise-primary text-sm">+ Add Fee</button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Fee Name</th>
                        <th>Price (PHP)</th>
                        <th>Unit Logic</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                    <tr>
                        <td class="font-bold text-slate-700">{{ $fee->name }}</td>
                        <td class="font-mono">₱{{ number_format($fee->price, 2) }}</td>
                        <td>
                            @if($fee->unit === 'per_use') <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-rose-50 text-rose-600 border border-rose-200">Per Use</span>
                            @elseif($fee->unit === 'per_day') <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-sky-500 text-white">Per Day</span>
                            @else <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-amber-300 text-amber-900">Flat Rate</span>
                            @endif
                        </td>
                        <td>
                            @if($fee->is_active)
                                <span class="text-md text-emerald-600 font-bold">Active</span>
                            @else
                                <span class="text-md text-rose-600">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center gap-2 justify-end">
                                <button @click="openEdit({{ $fee }})" class="btn-enterprise-primary text-xs py-1.5 px-3">Edit</button>

                                <form action="{{ route('accountant.fees.destroy', $fee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this fee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-enterprise-danger text-xs py-1.5 px-3">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">No fees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $fees->links() }}
        </div>
    </div>

    <!-- SHARED MODAL (Create & Edit) -->
    <dialog id="fee_modal" class="modal">
        <div class="modal-enterprise">
            <h3 class="font-bold text-lg mb-4" x-text="editMode ? 'Edit Fee' : 'Create New Fee'"></h3>

            <form :action="editMode ? editAction : '{{ route('accountant.fees.store') }}'" method="POST">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="form-control w-full mb-4">
                    <label class="label font-semibold text-slate-700">Fee Name</label>
                    <input type="text" name="name" x-model="editName" class="input-enterprise w-full" placeholder="e.g. Ambulance, ECG Reading" required />
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-control w-full">
                        <label class="label font-semibold text-slate-700">Price</label>
                        <input type="number" step="0.01" name="price" x-model="editPrice" class="input-enterprise w-full" placeholder="0.00" required />
                    </div>
                    <div class="form-control w-full">
                        <label class="label font-semibold text-slate-700">Unit Logic</label>
                        <select name="unit" x-model="editUnit" class="select-enterprise w-full">
                            <option value="per_use">Per Use (Standard)</option>
                            <option value="per_day">Per Day (Recurring)</option>
                            <option value="flat">Flat Rate</option>
                        </select>
                    </div>
                </div>

                <template x-if="editMode">
                    <div class="form-control w-full mb-4 px-0">
                        <label class="label font-semibold text-slate-700">Status</label>
                        <input type="hidden" name="is_active" x-bind:value="editIsActive ? 1 : 0">
                        <label class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">Inactive</span>
                            <input type="checkbox" x-model="editIsActive" class="toggle toggle-primary" />
                            <span class="text-sm text-gray-800">Active</span>
                        </label>
                    </div>
                </template>

                <div class="modal-action">
                    <button type="button" class="btn-enterprise-secondary" onclick="fee_modal.close()">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary" x-text="editMode ? 'Update Fee' : 'Save Fee'">Save</button>
                </div>
            </form>
        </div>
    </dialog>

</div>
@endsection
