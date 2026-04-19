@props(['post', 'dense' => false])

@php
    $reactions = $post->reactions;
    $topCount = $reactions->filter(fn($r) => optional($r->appreciation)->type === 'TOP')->count();
    $flopCount = $reactions->filter(fn($r) => optional($r->appreciation)->type === 'FLOP')->count();
    $userReaction = auth()->check() ? $reactions->firstWhere('user_id', auth()->id()) : null;
    $userType = $userReaction ? optional($userReaction->appreciation)->type : null;
@endphp

<div
    class="flex {{ $dense ? 'flex-col items-center justify-center px-1.5 py-2 border-r border-slate-100 bg-slate-50/50' : 'items-center gap-3 bg-white p-2 rounded-lg border border-slate-100 shadow-sm inline-flex' }}">
    <!-- TOP Button -->
    <form action="{{ route('react.post', $post) }}" method="POST" class="inline m-0">
        @csrf
        <input type="hidden" name="type" value="TOP">
        <button type="submit" title="Voter TOP"
            class="group flex flex-col items-center justify-center p-1.5 rounded-lg transition-all duration-300 relative overflow-hidden
            {{ $userType === 'TOP' ? 'text-green-600 bg-green-100 ring-1 ring-green-200' : 'text-slate-400 hover:text-green-600 hover:bg-green-50' }}">
            <svg class="w-6 h-6 transition-transform duration-300 ease-out group-hover:-translate-y-1 group-active:scale-90"
                fill="{{ $userType === 'TOP' ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
            </svg>
            @if(!$dense)
                <span class="text-[10px] font-black tracking-wider uppercase mt-1">Top</span>
            @endif
        </button>
    </form>

    <!-- Score global (Top - Flop) -->
    <div class="text-sm font-black text-slate-700 py-1 px-1 text-center min-w-[28px]"
        title="{{ $topCount }} Top, {{ $flopCount }} Flop">
        {{ $topCount - $flopCount }}
    </div>

    <!-- FLOP Button -->
    <form action="{{ route('react.post', $post) }}" method="POST" class="inline m-0">
        @csrf
        <input type="hidden" name="type" value="FLOP">
        <button type="submit" title="Voter FLOP"
            class="group flex flex-col items-center justify-center p-1.5 rounded-lg transition-all duration-300 relative overflow-hidden
            {{ $userType === 'FLOP' ? 'text-red-600 bg-red-100 ring-1 ring-red-200' : 'text-slate-400 hover:text-red-600 hover:bg-red-50' }}">
            @if(!$dense)
                <span class="text-[10px] font-black tracking-wider uppercase mb-1">Flop</span>
            @endif
            <svg class="w-6 h-6 transition-transform duration-300 ease-out group-hover:translate-y-1 group-active:scale-90"
                fill="{{ $userType === 'FLOP' ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </form>
</div>