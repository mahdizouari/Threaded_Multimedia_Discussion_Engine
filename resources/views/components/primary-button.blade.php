<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-transparent bg-gradient-to-r from-forum-600 to-forum-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-forum-900/40 transition hover:from-forum-500 hover:to-forum-400 focus:outline-none focus:ring-2 focus:ring-forum-400 focus:ring-offset-2 focus:ring-offset-slate-900 active:translate-y-px disabled:pointer-events-none disabled:opacity-50 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
