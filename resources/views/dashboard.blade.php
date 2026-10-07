@extends('layouts.app')

@section('title', 'AI Delay-Risk Dashboard')
@section('body_class', 'dashboard-theme')

@php
    $toneCard = [
        'blue' => 'border-blue-200 bg-blue-50',
        'green' => 'border-emerald-200 bg-emerald-50',
        'amber' => 'border-amber-200 bg-amber-50',
        'red' => 'border-red-200 bg-red-50',
    ];

    $toneIcon = [
        'blue' => 'text-blue-600',
        'green' => 'text-emerald-500',
        'amber' => 'text-amber-500',
        'red' => 'text-red-500',
    ];

    $donutRadius = 60;
    $donutCircumference = 2 * M_PI * $donutRadius;
@endphp

@section('content')
    <div
        class="flex min-h-screen"
        x-data="{
            parcels: @js($parcels),
            selectedId: @js($parcels[0]['tracking']),
            panelOpen: true,
            query: '',
            get rows() {
                const term = this.query.trim().toLowerCase();

                if (! term) {
                    return this.parcels;
                }

                return this.parcels.filter((parcel) =>
                    [parcel.tracking, parcel.area, parcel.carrier, parcel.service]
                        .join(' ')
                        .toLowerCase()
                        .includes(term)
                );
            },
            get selected() {
                return this.parcels.find((parcel) => parcel.tracking === this.selectedId) ?? this.parcels[0];
            },
            select(tracking) {
                this.selectedId = tracking;
                this.panelOpen = true;
            },
            badgeClass(level) {
                return {
                    high: 'bg-red-500 text-white',
                    medium: 'bg-amber-400 text-white',
                    low: 'bg-emerald-500 text-white',
                }[level];
            },
            textClass(level) {
                return {
                    high: 'text-red-600',
                    medium: 'text-amber-600',
                    low: 'text-emerald-600',
                }[level];
            },
        }"
    >
        {{-- Sidebar --}}
        <aside class="hidden w-[232px] shrink-0 flex-col justify-between bg-[var(--ops-sidebar)] lg:flex">
            <div>
                <div class="flex flex-col items-center gap-1 px-6 py-7">
                    <div class="flex items-center gap-2">
                        <span class="font-heading text-2xl font-extrabold italic tracking-tight text-white">PHLPOST</span>
                        <svg class="size-6 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M2 21l20-9L2 3v7l12 2-12 2v7z" />
                        </svg>
                    </div>
                    <p class="text-xs font-medium tracking-[0.18em] text-slate-300">PHLPOST</p>
                </div>

                <nav class="mt-2 flex flex-col" aria-label="Main navigation">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ $item['current'] ? route('dashboard') : '#' }}"
                            class="ops-nav-link"
                            @if ($item['current']) aria-current="page" @endif
                        >
                            <span class="shrink-0" aria-hidden="true">
                                @switch($item['icon'])
                                    @case('home')
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5" /><path d="M5 9.5V21h14V9.5" /><path d="M9.5 21v-6h5v6" /></svg>
                                        @break
                                    @case('package')
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2" /><path d="M3 9h18" /><path d="M9 4v5" /></svg>
                                        @break
                                    @case('handover')
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M8 12.5 11 15.5l5-6" /></svg>
                                        @break
                                    @case('brain')
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4a3 3 0 0 0-3 3 3 3 0 0 0-1 5.8A3 3 0 0 0 7.5 19 3 3 0 0 0 12 17.5V5.5A3 3 0 0 0 9 4Z" /><path d="M15 4a3 3 0 0 1 3 3 3 3 0 0 1 1 5.8A3 3 0 0 1 16.5 19 3 3 0 0 1 12 17.5" /></svg>
                                        @break
                                    @case('report')
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" /><path d="M14 3v5h5" /><path d="M9 13h6" /><path d="M9 17h4" /></svg>
                                        @break
                                    @default
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-2.9 1.2V21a2 2 0 1 1-4 0v-.1A1.7 1.7 0 0 0 7 19.4a1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0-1.2-2.9H1a2 2 0 1 1 0-4h.1A1.7 1.7 0 0 0 2.6 7a1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.7 1.7 0 0 0 8 2.6V2a2 2 0 1 1 4 0" /></svg>
                                @endswitch
                            </span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="px-6 py-8 text-slate-300">
                <p class="text-sm italic leading-6">Reliable. Efficient.<br>For a Better Tomorrow.</p>
                <p class="mt-5 font-heading text-lg font-bold italic text-white">PHLPOST</p>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="flex flex-col gap-4 border-b border-[var(--ops-border)] bg-white px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-7">
                <div class="min-w-0">
                    <h1 class="max-w-3xl text-xl font-bold leading-snug text-[var(--ops-heading)] lg:text-[1.6rem]">
                        AI-Assisted Last-Mile Parcel Management and Delay-Risk Decision Support System
                    </h1>
                    <p class="mt-1.5 text-sm font-semibold text-[var(--ops-muted)]">{{ $facility }}</p>
                </div>

                <div class="flex shrink-0 items-center gap-5">
                    <button type="button" class="relative rounded-full p-2 text-slate-500 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <span class="sr-only">Notifications ({{ $notifications }} unread)</span>
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" /><path d="M13.7 21a2 2 0 0 1-3.4 0" /></svg>
                        <span class="absolute -right-0.5 -top-0.5 flex size-5 items-center justify-center rounded-full bg-red-500 text-[11px] font-bold text-white" aria-hidden="true">{{ $notifications }}</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <span class="flex size-10 items-center justify-center rounded-full bg-slate-200 text-slate-600" aria-hidden="true">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8.5" r="3.5" /><path d="M4.5 20a7.5 7.5 0 0 1 15 0" /></svg>
                        </span>
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-[var(--ops-heading)]">{{ $currentUser->name }}</p>
                            <p class="text-xs text-[var(--ops-muted)]">{{ $currentUser->is_admin ? 'Operations Officer' : 'Team Member' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <span class="sr-only">Sign out</span>
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="flex flex-1 flex-col gap-5 p-5 xl:flex-row xl:items-start xl:gap-5 xl:p-6">
                <main class="flex min-w-0 flex-1 flex-col gap-5">
                    {{-- KPI cards --}}
                    <section aria-label="Daily parcel summary" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($stats as $stat)
                            <article class="rounded-xl border p-4 shadow-[var(--shadow-card)] {{ $toneCard[$stat['tone']] }}">
                                <div class="flex items-start gap-3">
                                    <span class="shrink-0 {{ $toneIcon[$stat['tone']] }}" aria-hidden="true">
                                        @switch($stat['tone'])
                                            @case('blue')
                                                <span class="flex size-10 items-center justify-center rounded-lg bg-blue-100">
                                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" /><path d="m4 7.5 8 4.5 8-4.5" /><path d="M12 12v9" /></svg>
                                                </span>
                                                @break
                                            @case('green')
                                                <svg class="size-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="m8 12.5 2.8 2.8L16 9.5" /></svg>
                                                @break
                                            @case('amber')
                                                <svg class="size-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7.5V12l3 2" /></svg>
                                                @break
                                            @default
                                                <svg class="size-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 4.3 2.6 17.5A1.9 1.9 0 0 0 4.3 20.4h15.4a1.9 1.9 0 0 0 1.7-2.9L13.7 4.3a1.9 1.9 0 0 0-3.4 0Z" /><path d="M12 9.5V14" /><path d="M12 17.2h.01" /></svg>
                                        @endswitch
                                    </span>
                                    <p class="pt-1 text-sm font-semibold leading-snug text-[var(--ops-body)]">{{ $stat['label'] }}</p>
                                </div>

                                <div class="mt-2 flex items-end justify-between gap-3">
                                    <p class="font-heading text-[2.1rem] font-bold leading-none text-[var(--ops-heading)]">{{ $stat['value'] }}</p>
                                    <div class="text-right">
                                        @if ($stat['meta'])
                                            <p class="text-xs font-semibold text-emerald-600">{{ $stat['meta'] }}</p>
                                        @endif
                                        <p class="text-xs text-[var(--ops-muted)]">{{ $stat['detail'] }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </section>

                    {{-- Charts --}}
                    <section class="grid gap-5 lg:grid-cols-2">
                        <article class="ops-card p-5">
                            <h2 class="text-base font-bold text-[var(--ops-heading)]">Delay-Risk Distribution</h2>

                            <div class="mt-5 flex flex-col items-center gap-6 sm:flex-row sm:items-center">
                                <div class="relative shrink-0">
                                    <svg viewBox="0 0 160 160" class="size-[160px]" role="img" aria-label="Delay risk distribution donut chart">
                                        @php $offset = 0; @endphp
                                        @foreach ($riskDistribution['segments'] as $segment)
                                            @php
                                                $length = $donutCircumference * ($segment['percent'] / 100);
                                            @endphp
                                            <circle
                                                cx="80" cy="80" r="{{ $donutRadius }}"
                                                fill="none"
                                                stroke="{{ $segment['color'] }}"
                                                stroke-width="22"
                                                stroke-dasharray="{{ round($length, 2) }} {{ round($donutCircumference - $length, 2) }}"
                                                stroke-dashoffset="{{ round(-$offset, 2) }}"
                                                transform="rotate(-90 80 80)"
                                            ></circle>
                                            @php $offset += $length; @endphp
                                        @endforeach
                                    </svg>
                                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="font-heading text-2xl font-bold text-[var(--ops-heading)]">{{ $riskDistribution['total'] }}</span>
                                        <span class="text-xs text-[var(--ops-muted)]">Total Parcels</span>
                                    </div>
                                </div>

                                <ul class="w-full space-y-3">
                                    @foreach ($riskDistribution['segments'] as $segment)
                                        <li class="flex items-center justify-between gap-3 text-sm">
                                            <span class="flex items-center gap-2.5 text-[var(--ops-body)]">
                                                <span class="size-2.5 rounded-full" style="background: {{ $segment['color'] }}" aria-hidden="true"></span>
                                                {{ $segment['label'] }}
                                            </span>
                                            <span class="font-semibold text-[var(--ops-heading)]">{{ $segment['value'] }} ({{ $segment['percent'] }}%)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>

                        <article class="ops-card p-5">
                            <h2 class="text-base font-bold text-[var(--ops-heading)]">Risk Trend (Last 7 Days)</h2>

                            <div class="mt-5 flex gap-3">
                                <div class="flex h-[160px] flex-col justify-between text-right text-xs text-[var(--ops-muted)]" aria-hidden="true">
                                    @foreach ([40, 30, 20, 10, 0] as $tick)
                                        <span>{{ $tick }}</span>
                                    @endforeach
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="relative h-[160px]">
                                        <div class="absolute inset-0 flex flex-col justify-between" aria-hidden="true">
                                            @for ($i = 0; $i < 5; $i++)
                                                <span class="block h-px w-full bg-slate-100"></span>
                                            @endfor
                                        </div>

                                        <div class="relative flex h-full items-end justify-between gap-2">
                                            @foreach ($riskTrend['days'] as $day)
                                                @php $dayTotal = $day['high'] + $day['medium'] + $day['low']; @endphp
                                                <div
                                                    class="flex h-full w-full max-w-[34px] flex-col justify-end"
                                                    title="{{ $day['label'] }} — High {{ $day['high'] }}, Medium {{ $day['medium'] }}, Low {{ $day['low'] }}"
                                                >
                                                    <span class="block w-full rounded-t-[3px] bg-red-500" style="height: {{ round($day['high'] / $riskTrend['max'] * 100, 2) }}%"></span>
                                                    <span class="block w-full bg-amber-400" style="height: {{ round($day['medium'] / $riskTrend['max'] * 100, 2) }}%"></span>
                                                    <span class="block w-full bg-emerald-500" style="height: {{ round($day['low'] / $riskTrend['max'] * 100, 2) }}%"></span>
                                                    <span class="sr-only">{{ $day['label'] }}: {{ $dayTotal }} flagged parcels</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-2 flex justify-between gap-2 text-xs text-[var(--ops-muted)]">
                                        @foreach ($riskTrend['days'] as $day)
                                            <span class="w-full max-w-[34px] text-center">{{ $day['label'] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <ul class="mt-4 flex items-center justify-center gap-6 text-xs text-[var(--ops-body)]">
                                @foreach ($riskTrend['legend'] as $legend)
                                    <li class="flex items-center gap-2">
                                        <span class="size-2.5 rounded-sm" style="background: {{ $legend['color'] }}" aria-hidden="true"></span>
                                        {{ $legend['label'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    </section>

                    {{-- Prioritization table --}}
                    <section class="ops-card">
                        <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-base font-bold text-[var(--ops-heading)]">AI Delay-Risk Prioritization</h2>

                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <label for="parcel-search" class="sr-only">Search tracking number, area, or carrier</label>
                                    <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
                                    <input
                                        id="parcel-search"
                                        x-model="query"
                                        type="search"
                                        placeholder="Search tracking number, area, or carrier..."
                                        class="w-full rounded-lg border border-[var(--ops-border)] bg-white py-2 pl-9 pr-3 text-sm text-[var(--ops-body)] placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 sm:w-72"
                                    >
                                </div>

                                <button type="button" class="rounded-lg border border-[var(--ops-border)] bg-white p-2.5 text-slate-500 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <span class="sr-only">Filter parcels</span>
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h18l-7 8v5l-4 2v-7Z" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[920px] border-collapse text-sm">
                                <caption class="sr-only">Parcels ranked by AI predicted delay risk</caption>
                                <thead>
                                    <tr class="border-y border-[var(--ops-border)] bg-slate-50/60 text-left text-xs font-semibold text-[var(--ops-body)]">
                                        <th scope="col" class="px-3 py-3 font-semibold">#</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Tracking No.</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Service Type</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Destination Area</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Assigned Carrier</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Parcel Age</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Delay Risk %</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Risk Level</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Priority</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Status</th>
                                        <th scope="col" class="px-3 py-3 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--ops-border)]">
                                    <template x-for="parcel in rows" :key="parcel.tracking">
                                        <tr class="ops-row" :data-selected="parcel.tracking === selectedId">
                                            <td class="px-3 py-3 text-[var(--ops-muted)]" x-text="parcel.index"></td>
                                            <td class="px-3 py-3">
                                                <button type="button" class="font-semibold text-blue-600 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" @click="select(parcel.tracking)" x-text="parcel.tracking"></button>
                                            </td>
                                            <td class="px-3 py-3 text-[var(--ops-body)]" x-text="parcel.service"></td>
                                            <td class="px-3 py-3 text-[var(--ops-body)]" x-text="parcel.area"></td>
                                            <td class="px-3 py-3 text-[var(--ops-body)]" x-text="parcel.carrier"></td>
                                            <td class="px-3 py-3 text-[var(--ops-body)]" x-text="parcel.age"></td>
                                            <td class="px-3 py-3 font-semibold" :class="textClass(parcel.level)" x-text="parcel.risk + '%'"></td>
                                            <td class="px-3 py-3">
                                                <span class="inline-flex rounded px-2 py-1 text-[11px] font-bold tracking-wide" :class="badgeClass(parcel.level)" x-text="parcel.levelLabel"></span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="flex size-6 items-center justify-center rounded-full text-[11px] font-bold text-white" :class="badgeClass(parcel.level)" x-text="parcel.priority"></span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="inline-flex rounded bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700" x-text="parcel.status"></span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <button type="button" class="rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" @click="select(parcel.tracking)">
                                                    View<span class="sr-only"> details for <span x-text="parcel.tracking"></span></span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="rows.length === 0">
                                        <td colspan="11" class="px-3 py-10 text-center text-sm text-[var(--ops-muted)]">No parcels match your search.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-col gap-3 border-t border-[var(--ops-border)] p-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-[var(--ops-muted)]">
                                Showing {{ $pagination['from'] }}–{{ $pagination['to'] }} of {{ number_format($pagination['total']) }} parcels
                            </p>

                            <nav class="flex items-center gap-1.5" aria-label="Parcel pagination">
                                <button type="button" class="flex size-8 items-center justify-center rounded-md border border-[var(--ops-border)] text-slate-400" disabled aria-label="Previous page">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m14 6-6 6 6 6" /></svg>
                                </button>

                                @foreach ($pagination['pages'] as $page)
                                    <a
                                        href="#"
                                        class="flex size-8 items-center justify-center rounded-md border text-sm font-medium transition {{ $page === $pagination['current'] ? 'border-blue-600 bg-blue-600 text-white' : 'border-[var(--ops-border)] text-[var(--ops-body)] hover:bg-slate-50' }}"
                                        @if ($page === $pagination['current']) aria-current="page" @endif
                                    >{{ $page }}</a>
                                @endforeach

                                <span class="px-1 text-sm text-[var(--ops-muted)]" aria-hidden="true">...</span>

                                <a href="#" class="flex size-8 items-center justify-center rounded-md border border-[var(--ops-border)] text-sm font-medium text-[var(--ops-body)] transition hover:bg-slate-50">{{ $pagination['last'] }}</a>

                                <a href="#" class="flex size-8 items-center justify-center rounded-md border border-[var(--ops-border)] text-slate-500 transition hover:bg-slate-50" aria-label="Next page">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10 6 6 6-6 6" /></svg>
                                </a>
                            </nav>
                        </div>
                    </section>
                </main>

                {{-- Parcel details panel --}}
                <aside
                    class="ops-card w-full shrink-0 self-stretch p-5 xl:w-[340px]"
                    x-show="panelOpen"
                    x-cloak
                    aria-label="Parcel details"
                >
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-base font-bold text-[var(--ops-heading)]">Parcel Details</h2>
                        <button type="button" class="rounded p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" @click="panelOpen = false">
                            <span class="sr-only">Close parcel details</span>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6 18 18M18 6 6 18" /></svg>
                        </button>
                    </div>

                    <div class="mt-5 border-t border-[var(--ops-border)] pt-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-heading text-lg font-bold text-[var(--ops-heading)]" x-text="selected.tracking"></p>
                            <span class="rounded px-2 py-1 text-[11px] font-bold" :class="badgeClass(selected.level)" x-text="selected.levelLabel + ' RISK'"></span>
                        </div>

                        <p class="mt-2">
                            <span class="inline-flex rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700" x-text="'Priority ' + selected.priority"></span>
                        </p>

                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Service Type</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.service"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Destination Area</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.area"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Assigned Carrier</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.carrier"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Date Received</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.received"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Parcel Age</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.age"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-muted)]">Current Status</dt>
                                <dd class="font-medium text-[var(--ops-heading)]" x-text="selected.currentStatus"></dd>
                            </div>
                        </dl>
                    </div>

                    {{-- AI prediction --}}
                    <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                        <h3 class="flex items-center gap-2 text-sm font-bold text-[var(--ops-heading)]">
                            <svg class="size-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4a3 3 0 0 0-3 3 3 3 0 0 0-1 5.8A3 3 0 0 0 7.5 19 3 3 0 0 0 12 17.5V5.5A3 3 0 0 0 9 4Z" /><path d="M15 4a3 3 0 0 1 3 3 3 3 0 0 1 1 5.8A3 3 0 0 1 16.5 19 3 3 0 0 1 12 17.5" /></svg>
                            AI Prediction
                        </h3>

                        <div class="mt-3 flex items-center gap-4">
                            <div class="relative shrink-0">
                                <svg viewBox="0 0 100 100" class="size-[86px] -rotate-90" role="img" :aria-label="'Delay probability ' + selected.risk + ' percent'">
                                    <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" stroke-width="9"></circle>
                                    <circle
                                        cx="50" cy="50" r="42" fill="none" stroke-width="9" stroke-linecap="round"
                                        :stroke="{ high: '#ef4444', medium: '#f59e0b', low: '#22c55e' }[selected.level]"
                                        stroke-dasharray="263.89"
                                        :stroke-dashoffset="263.89 - (263.89 * selected.risk / 100)"
                                    ></circle>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center font-heading text-lg font-bold text-[var(--ops-heading)]" x-text="selected.risk + '%'"></span>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-[var(--ops-heading)]">Delay Probability</p>
                                <span class="mt-2 inline-flex rounded px-2 py-1 text-[11px] font-bold" :class="badgeClass(selected.level)" x-text="selected.levelLabel + ' RISK'"></span>
                            </div>
                        </div>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-body)]">Risk Classification</dt>
                                <dd class="font-semibold" :class="textClass(selected.level)" x-text="selected.classification"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <dt class="text-[var(--ops-body)]">Priority Level</dt>
                                <dd>
                                    <span class="inline-flex rounded px-2 py-0.5 text-xs font-semibold" :class="badgeClass(selected.level)" x-text="selected.priorityLabel"></span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- AI recommendation --}}
                    <div class="mt-4 rounded-xl border border-red-100 bg-red-50/70 p-4">
                        <h3 class="flex items-center gap-2 text-sm font-bold text-red-700">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 4.3 2.6 17.5A1.9 1.9 0 0 0 4.3 20.4h15.4a1.9 1.9 0 0 0 1.7-2.9L13.7 4.3a1.9 1.9 0 0 0-3.4 0Z" /><path d="M12 9.5V14" /><path d="M12 17.2h.01" /></svg>
                            AI Recommendation
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-[var(--ops-body)]" x-text="selected.recommendation"></p>
                    </div>

                    {{-- History --}}
                    <div class="mt-5 border-t border-[var(--ops-border)] pt-4">
                        <h3 class="text-base font-bold text-[var(--ops-heading)]">Parcel History</h3>

                        <ol class="mt-4 space-y-5">
                            <template x-for="(event, index) in selected.history" :key="index">
                                <li class="flex gap-3">
                                    <span
                                        class="mt-1 size-3 shrink-0 rounded-full"
                                        :class="{ done: 'bg-emerald-500', active: 'bg-blue-500', pending: 'bg-slate-300' }[event.state]"
                                        aria-hidden="true"
                                    ></span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[var(--ops-heading)]" x-text="event.title"></p>
                                        <p class="mt-1 text-xs text-[var(--ops-muted)]">
                                            <span x-text="event.date"></span>
                                            <span x-show="event.time" class="ml-3" x-text="event.time"></span>
                                        </p>
                                        <p x-show="event.note" class="mt-1 text-xs text-[var(--ops-muted)]" x-text="event.note"></p>
                                    </div>
                                </li>
                            </template>
                        </ol>
                    </div>

                    <button type="button" class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14" /><path d="M5 12h14" /></svg>
                        Add to Priority List
                    </button>
                </aside>
            </div>

            <footer class="border-t border-[var(--ops-border)] bg-white px-6 py-4 text-center text-xs text-[var(--ops-muted)]">
                &copy; {{ now()->year }} {{ $facility }} &nbsp;|&nbsp; AI-Assisted Last-Mile Parcel Management and Delay-Risk Decision Support System
            </footer>
        </div>
    </div>
@endsection
