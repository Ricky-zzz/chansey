@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('nurse.headnurse.memos.index') }}" class="btn btn-sm btn-ghost normal-case h-9 min-h-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Send a memo to all staff in {{ $headNurse->station->station_name }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('nurse.headnurse.memos.store') }}" method="POST" enctype="multipart/form-data" id="memoForm">
        @csrf

        <div class="card-enterprise p-6 space-y-6">

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Memo Title <span class="text-red-500">*</span></label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="input-enterprise w-full @error('title') border-red-500 @enderror"
                       placeholder="e.g. Staff Meeting Reminder"
                       required>
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Content (CKEditor) --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Memo Content <span class="text-red-500">*</span></label>
                <textarea name="content"
                          id="content"
                          class="@error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Attachment --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Attachment (Optional)</label>
                <input type="file"
                       name="attachment"
                       class="file-input file-input-bordered w-full @error('attachment') border-red-500 @enderror"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <p class="text-xs text-slate-400 mt-1">Max 5MB. Accepted: PDF, Word, Images</p>
                @error('attachment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Target Info --}}
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Target Audience</h3>
                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1 mb-2">
                    <strong>Note:</strong> This memo will ONLY be sent to Staff Nurses in your station. Strict role matching is enforced.
                </p>
                <div class="space-y-2 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span><strong>Target Roles:</strong> Staff Nurses</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span><strong>Target Station:</strong> {{ $headNurse->station->station_name }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('nurse.headnurse.memos.index') }}" class="btn-enterprise-secondary">Cancel</a>
                <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send Memo
                </button>
            </div>

        </div>
    </form>

</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    let editorInstance;

    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Sync CKEditor content before form submission
    document.getElementById('memoForm').addEventListener('submit', function(e) {
        if (editorInstance) {
            const content = editorInstance.getData().trim();
            if (!content) {
                e.preventDefault();
                alert('Please enter memo content.');
                return false;
            }
            document.querySelector('#content').value = content;
        }
    });
</script>
@endpush
@endsection
