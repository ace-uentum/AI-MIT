<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the authenticated dashboard.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'currentUser' => $user,
            'stats' => [
                [
                    'label' => 'Total users',
                    'value' => str_pad((string) User::count(), 2, '0', STR_PAD_LEFT),
                    'detail' => 'Provisioned workspace access',
                ],
                [
                    'label' => 'Admin accounts',
                    'value' => str_pad((string) User::where('is_admin', true)->count(), 2, '0', STR_PAD_LEFT),
                    'detail' => 'Privileged operators online',
                ],
                [
                    'label' => 'Session driver',
                    'value' => strtoupper((string) config('session.driver')),
                    'detail' => 'Guarded web authentication',
                ],
                [
                    'label' => 'Queue mode',
                    'value' => strtoupper((string) config('queue.default')),
                    'detail' => 'Background work orchestration',
                ],
            ],
            'workspaceCards' => [
                [
                    'title' => 'Access posture',
                    'body' => 'Session-backed authentication is active, remember me is supported, and logout rotates the CSRF token cleanly.',
                ],
                [
                    'title' => 'Admin readiness',
                    'body' => 'A seeded administrator account is available for local onboarding and can be overridden with environment variables.',
                ],
                [
                    'title' => 'UI direction',
                    'body' => 'The dashboard follows the stored operations-style override with dense cards, restrained motion, and dark glass panels.',
                ],
            ],
            'timeline' => [
                [
                    'title' => 'Authentication ready',
                    'detail' => 'Login, logout, and route protection are wired for the web guard.',
                ],
                [
                    'title' => 'Seeded administrator',
                    'detail' => sprintf('Default account: %s', config('app.admin.email')),
                ],
                [
                    'title' => 'Last refresh window',
                    'detail' => now()->setTimezone(config('app.timezone'))->format('M d, Y • H:i'),
                ],
            ],
        ]);
    }
}
