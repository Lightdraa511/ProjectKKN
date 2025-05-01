<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Location;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::with(['faculty', 'location', 'profile']);

        // Filter berdasarkan fakultas
        if ($request->has('faculty') && $request->faculty != '') {
            $query->where('faculty_id', $request->faculty);
        }

        // Filter berdasarkan lokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('location_id', $request->location);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'completed':
                    $query->where('profile_completed', true);
                    break;
                case 'location_selected':
                    $query->where('location_id', '!=', null)
                        ->where('profile_completed', false);
                    break;
                case 'paid':
                    $query->where('payment_status', true)
                        ->where('location_id', null);
                    break;
                case 'pending':
                    $query->where('payment_status', false);
                    break;
            }
        }

        // Pencarian berdasarkan nama atau NIM
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('nim', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);
        $faculties = Faculty::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'faculties', 'locations'));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['faculty', 'location', 'profile']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $user->load(['faculty', 'location', 'profile']);
        $faculties = Faculty::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'faculties', 'locations'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'faculty_id' => 'required|exists:faculties,id',
            'location_id' => 'nullable|exists:locations,id',
            'payment_status' => 'boolean',
            'profile_completed' => 'boolean',
        ]);

        // Update data user
        $user->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'faculty_id' => $request->faculty_id,
            'location_id' => $request->location_id,
            'payment_status' => $request->has('payment_status'),
            'profile_completed' => $request->has('profile_completed'),
            'registration_status' => $this->determineRegistrationStatus($request),
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:8',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Hapus profil terkait jika ada
        if ($user->profile) {
            $user->profile->delete();
        }

        // Hapus user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }

    /**
     * Determine the registration status based on the request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function determineRegistrationStatus(Request $request)
    {
        if ($request->has('profile_completed')) {
            return 'completed';
        } elseif ($request->location_id) {
            return 'location_selected';
        } elseif ($request->has('payment_status')) {
            return 'payment_completed';
        } else {
            return 'registered';
        }
    }

    /**
     * Reset the progress of a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetProgress(User $user)
    {
        // Reset ke status baru mendaftar
        $user->update([
            'location_id' => null,
            'payment_status' => false,
            'profile_completed' => false,
            'registration_status' => 'registered',
        ]);

        // Hapus profil jika ada
        if ($user->profile) {
            $user->profile->delete();
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Progres pendaftaran mahasiswa berhasil direset.');
    }

    /**
     * Export users to CSV/Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = User::with(['faculty', 'location', 'profile']);

        // Apply the same filters as index
        if ($request->has('faculty') && $request->faculty != '') {
            $query->where('faculty_id', $request->faculty);
        }

        if ($request->has('location') && $request->location != '') {
            $query->where('location_id', $request->location);
        }

        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'completed':
                    $query->where('profile_completed', true);
                    break;
                case 'location_selected':
                    $query->where('location_id', '!=', null)
                        ->where('profile_completed', false);
                    break;
                case 'paid':
                    $query->where('payment_status', true)
                        ->where('location_id', null);
                    break;
                case 'pending':
                    $query->where('payment_status', false);
                    break;
            }
        }

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('nim', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->get();

        // Prepare CSV data
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mahasiswa_kkn_' . date('Y-m-d') . '.csv"',
        ];

        $columns = ['NIM', 'Nama', 'Email', 'Fakultas', 'Lokasi KKN', 'Status Pembayaran', 'Status Pendaftaran', 'Tanggal Daftar'];

        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row = [
                    $user->nim,
                    $user->name,
                    $user->email,
                    $user->faculty ? $user->faculty->name : '-',
                    $user->location ? $user->location->name : '-',
                    $user->payment_status ? 'Sudah Bayar' : 'Belum Bayar',
                    $this->getStatusLabel($user),
                    $user->created_at->format('d/m/Y H:i'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get the status label for a user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function getStatusLabel(User $user)
    {
        if ($user->profile_completed) {
            return 'Selesai';
        } elseif ($user->location_id) {
            return 'Lokasi Dipilih';
        } elseif ($user->payment_status) {
            return 'Pembayaran Selesai';
        } else {
            return 'Belum Bayar';
        }
    }
}
