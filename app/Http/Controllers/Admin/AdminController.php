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
        return view('admin.settings');
    }

    /**
     * Update settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        // Implementasi penyimpanan pengaturan
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
        return view('admin.account');
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
