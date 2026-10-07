@extends('layouts.app')

@section('title', 'Admin Login')
@section('body_class', 'bg-slate-950 text-slate-100')

@section('content')
    <main class="relative isolate overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(22,163,74,0.18),_transparent_28%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]"></div>

        <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-6 py-10 lg:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,480px)]">
                <section class="flex flex-col justify-between rounded-[2rem] border border-white/10 bg-white/8 p-8 shadow-[0_24px_80px_rgba(15,23,42,0.45)] backdrop-blur-xl sm:p-10">
                    <div class="max-w-2xl">
                        <span class="inline-flex rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
                            Protected operations workspace
                        </span>

                        <h1 class="mt-6 max-w-xl font-heading text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                            Sign in to the control center for your admin dashboard.
                        </h1>

                        <p class="mt-5 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                            Session-backed access, clear audit-friendly states, and a focused command surface built for daily operational work.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <article class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                            <p class="text-sm font-medium text-slate-400">Authentication</p>
                            <p class="mt-3 font-heading text-2xl font-semibold text-white">Session guard</p>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Web login with remember-me support and protected dashboard routes.</p>
                        </article>

                        <article class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                            <p class="text-sm font-medium text-slate-400">Accessibility</p>
                            <p class="mt-3 font-heading text-2xl font-semibold text-white">Paste allowed</p>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Password managers, autocomplete, visible focus states, and readable contrast.</p>
                        </article>

                        <article class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                            <p class="text-sm font-medium text-slate-400">System status</p>
                            <p class="mt-3 font-heading text-2xl font-semibold text-white">Admin ready</p>
                            <p class="mt-2 text-sm leading-6 text-slate-400">A seeded administrator account is available for immediate local access.</p>
                        </article>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-sky-200/20 bg-white p-8 text-slate-900 shadow-[0_24px_80px_rgba(15,23,42,0.45)] sm:p-10">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-700">Admin access</p>
                            <h2 class="mt-2 font-heading text-3xl font-semibold tracking-tight text-slate-950">Welcome back</h2>
                        </div>

                        <div class="rounded-2xl border border-sky-100 bg-sky-50 px-3 py-2 text-right">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">Guard</p>
                            <p class="mt-1 text-sm font-medium text-slate-700">Laravel session</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">We couldn't sign you in.</p>
                            <ul class="mt-2 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-6" novalidate>
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-semibold text-slate-700">Email address</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                class="block w-full rounded-2xl border border-sky-100 bg-slate-50 px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-900/10"
                                placeholder="admin@example.com"
                            >
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-2xl border border-sky-100 bg-slate-50 px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-900/10"
                                placeholder="Enter your password"
                            >
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-700"
                                    @checked(old('remember'))
                                >
                                Keep this device signed in
                            </label>

                            <p class="text-sm text-slate-500">Use your seeded administrator account to continue.</p>
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-base font-semibold text-white transition duration-200 hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-600/30"
                        >
                            Open dashboard
                        </button>
                    </form>

                    @if ($demoCredentials)
                        <div class="mt-8 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Local seeded account</p>
                            <dl class="mt-3 space-y-3 text-sm text-slate-700">
                                <div class="flex items-center justify-between gap-4">
                                    <dt>Email</dt>
                                    <dd class="font-mono text-xs sm:text-sm">{{ $demoCredentials['email'] }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt>Password</dt>
                                    <dd class="font-mono text-xs sm:text-sm">{{ $demoCredentials['password'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>
@endsection
