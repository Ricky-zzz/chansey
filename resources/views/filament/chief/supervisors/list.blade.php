@php
    $resource = $this->getResource();
@endphp

<div>
    {{ $this->table }}
</div>

@push('scripts')
<script>
    document.addEventListener('filament:table:record-action', (event) => {
        if (event.detail.action === 'viewStats') {
        }
    });
</script>
@endpush
