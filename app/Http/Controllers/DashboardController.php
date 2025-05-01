<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Metode middleware harus diterapkan melalui route middleware
        // bukan melalui controller di Laravel 12
    }

    /**
     * Show the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $locations = Location::with('facultyQuotas.faculty')->get();
        $totalLocations = $locations->count();
        $totalUsers = User::count();

        // Determine current step
        $currentStep = 1;
        if ($user->profile_completed) {
            $currentStep = 5;
        } elseif ($user->location_id) {
            $currentStep = 4;
        } elseif ($user->payment_status) {
            $currentStep = 3;
        } elseif ($user->registration_status != 'pending') {
            $currentStep = 2;
        }

        // Set status message
        switch ($user->registration_status) {
            case 'completed':
                $statusMessage = 'Pendaftaran KKN Anda telah selesai.';
                $statusClass = 'alert-success';
                $registrationStatus = 'Selesai';
                break;
            case 'location_selected':
                $statusMessage = 'Anda telah memilih lokasi KKN. Silakan lengkapi data diri untuk menyelesaikan pendaftaran.';
                $statusClass = 'alert-warning';
                $registrationStatus = 'Belum Lengkap';
                break;
            case 'payment_completed':
                $statusMessage = 'Pembayaran Anda telah diterima. Silakan pilih lokasi KKN.';
                $statusClass = 'alert-warning';
                $registrationStatus = 'Belum Lengkap';
                break;
            default:
                $statusMessage = 'Silakan lakukan pembayaran untuk melanjutkan pendaftaran KKN.';
                $statusClass = 'alert-info';
                $registrationStatus = 'Belum Bayar';
                break;
        }

        return view('dashboard.index', compact(
            'locations',
            'totalLocations',
            'totalUsers',
            'currentStep',
            'statusMessage',
            'statusClass',
            'registrationStatus'
        ));
    }
}
