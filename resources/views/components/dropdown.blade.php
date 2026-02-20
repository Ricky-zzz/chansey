@props(['align' => 'left', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

<div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:leave="transition ease-in duration-150"
            @click="open = false"
            class="absolute z-50 mt-2 rounded-md shadow-lg {{ $align === 'right' ? 'right-0' : 'left-0' }} {{ 'w-' . $width }}"
            style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
