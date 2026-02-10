@php /** @var \App\Models\Memo $memo */ @endphp
<div class="p-4">
    <h2 class="text-xl font-bold mb-2">{{ $memo->title }}</h2>
    <div class="mb-2 text-sm text-gray-600">From: {{ $memo->created_by_user->name ?? 'Unknown' }}</div>
    <div class="mb-2 text-sm text-gray-600">Date: {{ $memo->created_at->format('M d, Y H:i') }}</div>
    <div class="mb-4 text-gray-800">{!! nl2br(e($memo->body ?? '')) !!}</div>
    <div class="text-xs text-gray-500">Target Roles: {{ implode(', ', $memo->target_roles ?? []) }}</div>
    <div class="text-xs text-gray-500">Target Units: {{ implode(', ', $memo->target_units ?? []) }}</div>
</div>
