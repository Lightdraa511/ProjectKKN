<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the profile form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Jika belum memilih lokasi, redirect ke halaman pemilihan lokasi
        if (!$user->location_id) {
            return redirect()->route('locations.index')
                ->with('error', 'Anda harus memilih lokasi KKN terlebih dahulu sebelum mengisi profil.');
        }

        // Jika profil sudah lengkap, redirect ke halaman selesai
        if ($user->profile_completed) {
            return redirect()->route('profile.completion')
                ->with('info', 'Anda sudah melengkapi data diri.');
        }

        // Ambil data profil jika sudah ada
        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        return view('profile.index', compact('user', 'profile'));
    }

    /**
     * Store the profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'emergency_contact' => 'required|string',
            'blood_type' => 'required|string|in:A,B,AB,O',
            'disease_history' => 'nullable|string',
            'skills' => 'required|string',
        ]);

        // Ambil atau buat profil baru
        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        // Update data profil menggunakan kolom yang sesuai dengan model
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->emergency_contact = $request->emergency_contact;
        $profile->blood_type = $request->blood_type;
        $profile->disease_history = $request->disease_history;
        $profile->skills = $request->skills;

        $profile->save();

        // Update status pengguna
        $user->profile_completed = true;
        $user->registration_status = 'completed';
        $user->save();

        return redirect()->route('profile.completion')
            ->with('success', 'Data diri berhasil disimpan. Pendaftaran KKN Anda telah selesai.');
    }

    /**
     * Display the completion page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function completion()
    {
        $user = Auth::user();

        // Jika belum melengkapi profil, redirect ke halaman profil
        if (!$user->profile_completed) {
            return redirect()->route('profile.index')
                ->with('error', 'Anda belum melengkapi data diri.');
        }

        $profile = Profile::where('user_id', $user->id)->first();
        $location = $user->location;

        return view('profile.completion', compact('user', 'profile', 'location'));
    }
}
