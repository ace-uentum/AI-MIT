<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the authenticated operations dashboard.
     */
    public function __invoke(Request $request): View
    {
        return view('dashboard', [
            'currentUser' => $request->user(),
            'facility' => 'PhlPost Lipa City',
            'notifications' => 3,
            'navigation' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'current' => true],
                ['label' => 'Parcel Management', 'icon' => 'package', 'current' => false],
                ['label' => 'Handover Management', 'icon' => 'handover', 'current' => false],
                ['label' => 'AI Delay-Risk Analysis', 'icon' => 'brain', 'current' => false],
                ['label' => 'Reports', 'icon' => 'report', 'current' => false],
                ['label' => 'Settings', 'icon' => 'settings', 'current' => false],
            ],
            'stats' => [
                [
                    'label' => 'Total Parcels Today',
                    'value' => '482',
                    'meta' => '↑ 12%',
                    'detail' => 'vs. yesterday',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Delivered',
                    'value' => '361',
                    'meta' => null,
                    'detail' => '74.8%',
                    'tone' => 'green',
                ],
                [
                    'label' => 'Pending',
                    'value' => '98',
                    'meta' => null,
                    'detail' => '20.3%',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'At Risk (AI)',
                    'value' => '23',
                    'meta' => null,
                    'detail' => '4.8%',
                    'tone' => 'red',
                ],
            ],
            'riskDistribution' => [
                'total' => 482,
                'segments' => [
                    ['label' => 'High Risk', 'value' => 23, 'percent' => 4.8, 'color' => '#ef4444'],
                    ['label' => 'Medium Risk', 'value' => 61, 'percent' => 12.7, 'color' => '#f59e0b'],
                    ['label' => 'Low Risk', 'value' => 98, 'percent' => 20.3, 'color' => '#22c55e'],
                    ['label' => 'On-Time (Low Risk)', 'value' => 300, 'percent' => 62.2, 'color' => '#3b82f6'],
                ],
            ],
            'riskTrend' => [
                'max' => 40,
                'days' => [
                    ['label' => 'Oct 2', 'high' => 3, 'medium' => 5, 'low' => 10],
                    ['label' => 'Oct 3', 'high' => 2, 'medium' => 4, 'low' => 8],
                    ['label' => 'Oct 4', 'high' => 3, 'medium' => 6, 'low' => 12],
                    ['label' => 'Oct 5', 'high' => 4, 'medium' => 6, 'low' => 13],
                    ['label' => 'Oct 6', 'high' => 3, 'medium' => 5, 'low' => 11],
                    ['label' => 'Oct 7', 'high' => 4, 'medium' => 7, 'low' => 12],
                    ['label' => 'Oct 8', 'high' => 4, 'medium' => 6, 'low' => 14],
                ],
                'legend' => [
                    ['label' => 'High', 'color' => '#ef4444'],
                    ['label' => 'Medium', 'color' => '#f59e0b'],
                    ['label' => 'Low', 'color' => '#22c55e'],
                ],
            ],
            'parcels' => $this->parcels(),
            'pagination' => [
                'from' => 1,
                'to' => 10,
                'total' => 482,
                'current' => 1,
                'pages' => [1, 2, 3, 4, 5],
                'last' => 49,
            ],
        ]);
    }

    /**
     * Prioritised parcels for the current page of the AI ranking table.
     *
     * @return array<int, array<string, mixed>>
     */
    private function parcels(): array
    {
        $rows = [
            ['RX10001PH', 'EMS', 'Sabang', 'Robert', 1, 92, 1],
            ['RX10002PH', 'Parcel', 'San Carlos', 'Darwin', 2, 84, 2],
            ['RX10003PH', 'OSP', 'Antipolo Norte', 'Christian', 1, 79, 3],
            ['RX10004PH', 'Foreign Reg.', 'Mataas na Lupa', 'Robert', 2, 63, 4],
            ['RX10005PH', 'Parcel', 'Youngsville', 'Maria', 3, 58, 5],
            ['RX10006PH', 'EMS', 'San Isidro', 'Darwin', 1, 47, 6],
            ['RX10007PH', 'Parcel', 'Lipa Proper', 'Christian', 4, 32, 7],
            ['RX10008PH', 'OSP', 'Balete', 'Robert', 2, 25, 8],
            ['RX10009PH', 'Parcel', 'Cuenca', 'Maria', 1, 18, 9],
            ['RX10010PH', 'Foreign Reg.', 'San Jose', 'Darwin', 1, 12, 10],
        ];

        return array_map(function (array $row, int $index): array {
            [$tracking, $service, $area, $carrier, $age, $risk, $priority] = $row;

            $level = match (true) {
                $risk >= 70 => 'high',
                $risk >= 40 => 'medium',
                default => 'low',
            };

            return [
                'index' => $index + 1,
                'tracking' => $tracking,
                'service' => $service,
                'area' => $area,
                'carrier' => $carrier,
                'age' => $age.' '.($age === 1 ? 'day' : 'days'),
                'risk' => $risk,
                'level' => $level,
                'levelLabel' => strtoupper($level),
                'priority' => $priority,
                'priorityLabel' => $priority === 1 ? '1 (Highest)' : (string) $priority,
                'status' => 'Pending',
                'received' => 'Oct 7, 2026',
                'currentStatus' => 'Pending (At Lipa)',
                'classification' => ucfirst($level),
                'recommendation' => $this->recommendation($level),
                'history' => [
                    ['title' => 'Received at Lipa', 'date' => 'Oct 7, 2026', 'time' => '09:15 AM', 'note' => null, 'state' => 'done'],
                    ['title' => 'Handover to Carrier', 'date' => 'Oct 7, 2026', 'time' => '02:30 PM', 'note' => 'Carrier: '.$carrier, 'state' => 'active'],
                    ['title' => 'In Transit', 'date' => '-', 'time' => null, 'note' => null, 'state' => 'pending'],
                    ['title' => 'Expected Delivery', 'date' => 'Oct 10, 2026', 'time' => null, 'note' => null, 'state' => 'pending'],
                ],
            ];
        }, $rows, array_keys($rows));
    }

    private function recommendation(string $level): string
    {
        return match ($level) {
            'high' => 'This parcel has a high predicted risk of delayed delivery. Consider giving this parcel priority attention during the next handover/monitoring activity.',
            'medium' => 'This parcel shows a moderate delay risk. Monitor its next handover and escalate if it remains pending beyond the expected window.',
            default => 'This parcel is tracking within the expected delivery window. No additional intervention is required at this time.',
        };
    }
}
