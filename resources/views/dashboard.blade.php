@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('body_class', 'dashboard-theme')

@section('content')
    <main class="relative isolate min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(34,197,94,0.18),_transparent_24%),radial-gradient(circle_at_left,_rgba(59,130,246,0.15),_transparent_32%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]"></div>

        <div class="relative mx-auto flex min-h-screen w-full max-w-[1400px] flex-col px-6 py-6 lg:px-8">
            <header class="glass-panel flex flex-col gap-6 rounded-[2rem] px-6 py-6 sm:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Operations dashboard</p>
                        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                            Welcome back, {{ $currentUser->name }}.
                        </h1>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                            Your Laravel admin workspace is now secured behind a dedicated login experience and a protected dashboard route.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="rounded-2xl border border-emerald-400/25 bg-emerald-400/10 px-4 py-3 text-sm">
                            <p class="text-slate-300">Signed in as</p>
                            <p class="mt-1 font-medium text-white">{{ $currentUser->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/15"
                            >
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-300">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2">{{ $currentUser->is_admin ? 'Administrator' : 'Team member' }}</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2">Session secured</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2">Dashboard active</span>
                </div>
            </header>

            <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $stat)
                    <article class="stat-card grid-item rounded-[1.75rem] p-6">
                        <p class="text-sm font-medium text-slate-400">{{ $stat['label'] }}</p>
                        <p class="mt-4 font-mono text-4xl font-semibold tracking-tight text-white">{{ $stat['value'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $stat['detail'] }}</p>
                    </article>
                @endforeach
            </section>

            <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(360px,0.9fr)]">
                <div class="space-y-6">
                    <article class="glass-panel rounded-[1.9rem] p-6 sm:p-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div class="max-w-2xl">
                                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Workspace overview</p>
                                <h2 class="mt-3 text-2xl font-semibold tracking-tight text-white">A cleaner entry point for daily admin work</h2>
                                <p class="mt-4 text-sm leading-7 text-slate-300 sm:text-base">
                                    The new experience separates authentication from operations, keeps the login screen accessible, and surfaces environment health in a compact glassmorphism dashboard shell.
                                </p>
                            </div>

                            <div class="rounded-[1.5rem] border border-white/10 bg-slate-950/30 px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Seeded admin</p>
                                <p class="mt-2 font-mono text-sm text-white">{{ config('app.admin.email') }}</p>
                            </div>
                        </div>
                    </article>

                    <div class="grid gap-4 lg:grid-cols-3">
                        @foreach ($workspaceCards as $card)
                            <article class="stat-card grid-item rounded-[1.75rem] p-6">
                                <h3 class="text-lg font-semibold text-white">{{ $card['title'] }}</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-300">{{ $card['body'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="glass-panel rounded-[1.9rem] p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Timeline</p>
                    <h2 class="mt-3 text-2xl font-semibold tracking-tight text-white">Latest rollout details</h2>

                    <ol class="mt-6 space-y-5">
                        @foreach ($timeline as $item)
                            <li class="flex gap-4">
                                <span class="mt-1 h-3 w-3 rounded-full bg-emerald-400 shadow-[0_0_0_6px_rgba(34,197,94,0.12)]"></span>
                                <div>
                                    <h3 class="text-sm font-semibold text-white">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-300">{{ $item['detail'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-8 rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Access note</p>
                        <p class="mt-3 text-sm leading-7 text-slate-300">
                            Change <span class="font-mono text-white">ADMIN_EMAIL</span> and <span class="font-mono text-white">ADMIN_PASSWORD</span> in your environment if you want different bootstrap credentials.
                        </p>
                    </div>
                </aside>
            </section>
        </div>
    </main>
@endsection
