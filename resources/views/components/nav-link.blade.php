@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-forum-800 bg-forum-50 ring-1 ring-forum-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center rounded-full px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
