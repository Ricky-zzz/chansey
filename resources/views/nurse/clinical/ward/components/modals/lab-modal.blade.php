<!-- LAB UPLOAD MODAL -->
<dialog id="lab_modal" class="modal">
    <div class="modal-box modal-enterprise w-11/12 max-w-2xl">
        <h3 class="font-semibold text-lg text-slate-800 mb-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" />
            </svg>
            Upload Lab Result
        </h3>
        <p class="text-xs text-slate-500 mb-4">Complete the lab order by uploading findings and supporting documents</p>

        <form action="{{ route('nurse.clinical.orders.upload_result') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="medical_order_id" x-model="labOrderId">

            <!-- Order Context Alert -->
            <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-lg p-3 text-xs mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <strong>Order:</strong> <span x-text="labInstruction" class="font-mono"></span>
            </div>

            <!-- 1. The Finding -->
            <div class="form-control mb-5">
                <label class="label font-semibold text-xs uppercase text-slate-500 mb-1">
                    <span>Result / Finding</span>
                    <span class="text-red-500">*</span>
                </label>
                <textarea name="findings" class="textarea-enterprise h-24" placeholder="e.g. Normal, Fracture detected, High WBC count, Elevated cholesterol..." required></textarea>
                <label class="label text-xs text-slate-400">
                    <span>Describe the lab findings clearly</span>
                </label>
            </div>

            <!-- 2. The File Upload -->
            <div class="form-control mb-6">
                <label class="label font-semibold text-xs uppercase text-slate-500 mb-1">
                    <span>Scan / PDF / Image</span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="file" name="result_file" class="file-input file-input-bordered rounded-lg border-slate-300 bg-white w-full" accept=".pdf,.jpg,.png,.jpeg" required />
                <label class="label text-xs text-slate-400">
                    <span>Supported: PDF, JPG, PNG (Max 5MB)</span>
                </label>
            </div>

            <!-- Form Actions -->
            <div class="modal-action">
                <button type="button" class="btn-enterprise-secondary" onclick="lab_modal.close()">Cancel</button>
                <button type="submit" class="btn-enterprise-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-6-4m6 4l6-4" />
                    </svg>
                    Upload & Complete
                </button>
            </div>
        </form>
    </div>
</dialog>
