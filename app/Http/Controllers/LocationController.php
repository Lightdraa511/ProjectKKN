<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Faculty;
use App\Models\FacultyQuota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of available locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Cek apakah pembayaran sudah dilakukan
        if (!$user->payment_status) {
            return redirect()->route('payment.index')
                ->with('error', 'Anda harus melakukan pembayaran terlebih dahulu sebelum memilih lokasi KKN.');
        }

        // Cek apakah lokasi sudah dipilih
        if ($user->location_id) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah memilih lokasi KKN.');
        }

        // Ambil semua lokasi
        $locations = Location::with(['facultyQuotas.faculty'])->get();

        // Filter lokasi yang masih memiliki kuota untuk fakultas pengguna
        $locations = $locations->filter(function($location) use ($user) {
            $facultyQuota = $location->facultyQuotas->where('faculty_id', $user->faculty_id)->first();
            if (!$facultyQuota) {
                return false;
            }

            // Hitung jumlah mahasiswa dari fakultas yang sama yang sudah terdaftar di lokasi ini
            $assignedCount = User::where('location_id', $location->id)
                ->where('faculty_id', $user->faculty_id)
                ->count();

            return $assignedCount < $facultyQuota->quota;
        });

        return view('locations.index', compact('locations'));
    }

    /**
     * Show location selection confirmation form.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function select(Location $location)
    {
        $user = Auth::user();

        // Cek apakah pembayaran sudah dilakukan
        if (!$user->payment_status) {
            return redirect()->route('payment.index')
                ->with('error', 'Anda harus melakukan pembayaran terlebih dahulu sebelum memilih lokasi KKN.');
        }

        // Cek ketersediaan kuota untuk fakultas pengguna
        $facultyQuota = FacultyQuota::where('location_id', $location->id)
            ->where('faculty_id', $user->faculty_id)
            ->first();

        if (!$facultyQuota) {
            return redirect()->route('locations.index')
                ->with('error', 'Lokasi ini tidak tersedia untuk fakultas Anda.');
        }

        // Hitung jumlah mahasiswa dari fakultas yang sama yang sudah terdaftar di lokasi ini
        $assignedCount = User::where('location_id', $location->id)
            ->where('faculty_id', $user->faculty_id)
            ->count();

        if ($assignedCount >= $facultyQuota->quota) {
            return redirect()->route('locations.index')
                ->with('error', 'Kuota untuk fakultas Anda di lokasi ini sudah penuh.');
        }

        return view('locations.select', compact('location', 'facultyQuota'));
    }

    /**
     * Confirm location selection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, Location $location)
    {
        $user = Auth::user();

        // Cek apakah pembayaran sudah dilakukan
        if (!$user->payment_status) {
            return redirect()->route('payment.index')
                ->with('error', 'Anda harus melakukan pembayaran terlebih dahulu sebelum memilih lokasi KKN.');
        }

        // Cek apakah lokasi sudah dipilih
        if ($user->location_id) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah memilih lokasi KKN.');
        }

        // Cek ketersediaan kuota untuk fakultas pengguna
        $facultyQuota = FacultyQuota::where('location_id', $location->id)
            ->where('faculty_id', $user->faculty_id)
            ->first();

        if (!$facultyQuota) {
            return redirect()->route('locations.index')
                ->with('error', 'Lokasi ini tidak tersedia untuk fakultas Anda.');
        }

        // Hitung jumlah mahasiswa dari fakultas yang sama yang sudah terdaftar di lokasi ini
        $assignedCount = User::where('location_id', $location->id)
            ->where('faculty_id', $user->faculty_id)
            ->count();

        if ($assignedCount >= $facultyQuota->quota) {
            return redirect()->route('locations.index')
                ->with('error', 'Kuota untuk fakultas Anda di lokasi ini sudah penuh.');
        }

        // Update data pengguna
        $user->location_id = $location->id;
        $user->registration_status = 'location_selected';
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Lokasi KKN berhasil dipilih. Silakan lengkapi data diri Anda.');
    }

    /**
     * Cancel location selection.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        $user = Auth::user();

        // Cek apakah sudah memilih lokasi
        if (!$user->location_id) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda belum memilih lokasi KKN.');
        }

        // Jika sudah selesai pendaftaran, tidak boleh dibatalkan
        if ($user->profile_completed) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak dapat membatalkan lokasi karena pendaftaran sudah selesai.');
        }

        // Reset data pengguna
        $user->location_id = null;
        $user->registration_status = 'payment_completed';
        $user->save();

        return redirect()->route('locations.index')
            ->with('success', 'Pemilihan lokasi KKN berhasil dibatalkan. Silakan pilih lokasi lain.');
    }
}
