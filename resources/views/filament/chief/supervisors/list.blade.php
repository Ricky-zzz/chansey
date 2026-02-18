@php
    $resource = $this->getResource();
@endphp

<div>
    {{ $this->table }}
</div>

@push('scripts')
<script>
    // Handle stats modal opening
    document.addEventListener('filament:table:record-action', (event) => {
        if (event.detail.action === 'viewStats') {
            // The action will handle opening the modal
        }
    });
</script>
@endpush
