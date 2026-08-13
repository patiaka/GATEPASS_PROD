{{-- Toasts pour les pages invitées (login…), sans Livewire/Alpine.
     Rend les erreurs de validation + le message de session, auto-dismiss géré
     par gpInitToasts() dans layouts/guest. --}}
@php
    $gpToasts = [];
    foreach ($errors->all() as $gpErr) {
        $gpToasts[] = ['type' => 'error', 'msg' => $gpErr];
    }
    if (session('status')) {
        $gpToasts[] = ['type' => 'success', 'msg' => session('status')];
    }
@endphp

@if (count($gpToasts))
    <div id="gp-toasts" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 w-[calc(100%-2rem)] max-w-sm">
        @foreach ($gpToasts as $gpT)
            <div class="gp-toast flex items-start gap-3 rounded-xl border bg-white px-4 py-3 shadow-lg opacity-0 translate-x-4 transition-all duration-300
                {{ $gpT['type'] === 'error' ? 'border-rose-200' : 'border-emerald-200' }}" role="alert">
                <span class="mt-0.5 flex items-center justify-center w-6 h-6 shrink-0 rounded-full
                    {{ $gpT['type'] === 'error' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    @if ($gpT['type'] === 'error')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                </span>
                <p class="text-sm text-slate-700 flex-1 leading-snug">{{ $gpT['msg'] }}</p>
                <button type="button" onclick="gpDismissToast(this.closest('.gp-toast'))"
                    class="text-slate-400 hover:text-slate-600 shrink-0 transition" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
