<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Location;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle an admin login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the admin out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $totalLocations = Location::count();
        $totalUsers = User::count();
        $completedRegistrations = User::where('profile_completed', true)->count();
        $faculties = Faculty::withCount('users')->get();

        return view('admin.dashboard', compact(
            'totalLocations',
            'totalUsers',
            'completedRegistrations',
            'faculties'
        ));
    }

    /**
     * Show the users list.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::with(['faculty', 'location'])->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show a user's details.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function showUser(User $user)
    {
        $user->load(['faculty', 'location', 'profile', 'payment']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        // Ambil data pengaturan dari database atau dari file konfigurasi
        $settings = [
            'registration_start' => config('kkn.registration_start', now()->format('Y-m-d')),
            'registration_end' => config('kkn.registration_end', now()->addMonths(1)->format('Y-m-d')),
            'kkn_start' => config('kkn.kkn_start', now()->addMonths(2)->format('Y-m-d')),
            'kkn_end' => config('kkn.kkn_end', now()->addMonths(3)->format('Y-m-d')),
            'registration_fee' => config('kkn.registration_fee', 500000),
            'min_sks' => config('kkn.min_sks', 100),
            'min_gpa' => config('kkn.min_gpa', 2.75),
            'announcement' => config('kkn.announcement', '')
        ];

        // Tambahkan statistik untuk ditampilkan di sidebar
        $totalUsers = User::count();
        $completedRegistrations = User::where('profile_completed', true)->count();
        $totalLocations = Location::count();

        return view('admin.settings', array_merge($settings, [
            'totalUsers' => $totalUsers,
            'completedRegistrations' => $completedRegistrations,
            'totalLocations' => $totalLocations,
        ]));
    }

    /**
     * Update settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'kkn_start' => 'required|date',
            'kkn_end' => 'required|date|after:kkn_start',
            'registration_fee' => 'required|numeric|min:0',
            'min_sks' => 'required|numeric|min:0',
            'min_gpa' => 'required|numeric|min:0|max:4',
            'announcement' => 'nullable|string',
        ]);

        // Simpan pengaturan (misalnya ke dalam database atau file konfigurasi)
        // Di sini kita akan menggunakan config untuk sementara
        foreach ($validated as $key => $value) {
            config(['kkn.' . $key => $value]);
        }

        // Dalam implementasi nyata, simpan ke dalam database atau file konfigurasi

        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Show the account settings page.
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        // Tambahkan data login log jika ada
        $loginLogs = collect(); // Placeholder, sebaiknya ambil dari database jika ada

        return view('admin.account', [
            'loginLogs' => $loginLogs
        ]);
    }

    /**
     * Update admin account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.account')
            ->with('success', 'Password berhasil diubah.');
    }
}
