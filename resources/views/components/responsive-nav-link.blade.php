@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-lg ps-3 pe-4 py-2.5 text-start text-sm font-semibold text-forum-800 bg-forum-50 ring-1 ring-forum-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-500 transition duration-150 ease-in-out'
            : 'block w-full rounded-lg ps-3 pe-4 py-2.5 text-start text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
