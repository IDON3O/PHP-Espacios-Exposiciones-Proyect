<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Venue;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pending'   => Reservation::where('status', 'pending')->count(),
                'confirmed' => Reservation::where('status', 'confirmed')->count(),
                'total'     => Reservation::count(),
                'spaces'    => Venue::where('is_active', true)->count(),
            ],
            'recent' => Reservation::with('venue')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
