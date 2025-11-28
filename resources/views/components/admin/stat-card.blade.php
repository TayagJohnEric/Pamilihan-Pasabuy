@props([
    'label',
    'value',
    'icon' => null,
    'theme' => 'emerald',
])

@php
    $themes = [
        'emerald' => ['bg' => 'from-emerald-500 to-teal-500', 'text' => 'text-emerald-600', 'muted' => 'text-emerald-100'],
        'teal' => ['bg' => 'from-teal-500 to-cyan-500', 'text' => 'text-teal-600', 'muted' => 'text-teal-100'],
        'amber' => ['bg' => 'from-amber-500 to-orange-500', 'text' => 'text-amber-600', 'muted' => 'text-amber-100'],
        'sky' => ['bg' => 'from-sky-500 to-blue-500', 'text' => 'text-sky-600', 'muted' => 'text-sky-100'],
        'rose' => ['bg' => 'from-rose-500 to-pink-500', 'text' => 'text-rose-600', 'muted' => 'text-rose-100'],
        'slate' => ['bg' => 'from-slate-500 to-slate-600', 'text' => 'text-slate-600', 'muted' => 'text-slate-100'],
    ];

    $palette = $themes[$theme] ?? $themes['emerald'];

    $iconSvg = match($icon) {
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 14a4 4 0 10-8 0m12 4v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11a4 4 0 100-8 4 4 0 000 8z" />',
        'camera' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8h4l2-2h6l2 2h4v8H3z" /><circle cx="12" cy="12" r="3" stroke-width="1.5" />',
        'exclamation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z" />',
        'clock' => '<circle cx="12" cy="12" r="9" stroke-width="1.5" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7v5l3 2" />',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" /><circle cx="12" cy="12" r="9" stroke-width="1.5" />',
    };
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5 flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
        <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $value }}</p>
    </div>
    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $palette['bg'] }} flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            {!! $iconSvg !!}
        </svg>
    </div>
</div>
