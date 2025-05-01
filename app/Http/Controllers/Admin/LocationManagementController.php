<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\FacultyQuota;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationManagementController extends Controller
{
    /**
     * Display a listing of the locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::withCount('users')->get();

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $faculties = Faculty::all();

        return view('admin.locations.create', compact('faculties'));
    }

    /**
     * Store a newly created location in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'total_quota' => 'required|integer|min:1',
            'faculty_quotas' => 'required|array',
            'faculty_quotas.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Buat lokasi baru
            $location = Location::create([
                'name' => $request->name,
                'description' => $request->description,
                'total_quota' => $request->total_quota,
            ]);

            // Simpan kuota untuk setiap fakultas
            foreach ($request->faculty_quotas as $facultyId => $quota) {
                if ($quota > 0) {
                    FacultyQuota::create([
                        'location_id' => $location->id,
                        'faculty_id' => $facultyId,
                        'quota' => $quota,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.locations.index')
                ->with('success', 'Lokasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified location.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function show(Location $location)
    {
        $location->load('facultyQuotas.faculty');
        $registeredUsers = User::where('location_id', $location->id)
            ->with('faculty')
            ->get()
            ->groupBy('faculty_id');

        return view('admin.locations.show', compact('location', 'registeredUsers'));
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function edit(Location $location)
    {
        $faculties = Faculty::all();
        $location->load('facultyQuotas');

        // Siapkan data kuota untuk form
        $facultyQuotas = [];
        foreach ($faculties as $faculty) {
            $quota = $location->facultyQuotas->where('faculty_id', $faculty->id)->first();
            $facultyQuotas[$faculty->id] = $quota ? $quota->quota : 0;
        }

        return view('admin.locations.edit', compact('location', 'faculties', 'facultyQuotas'));
    }

    /**
     * Update the specified location in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'total_quota' => 'required|integer|min:1',
            'faculty_quotas' => 'required|array',
            'faculty_quotas.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Update data lokasi
            $location->update([
                'name' => $request->name,
                'description' => $request->description,
                'total_quota' => $request->total_quota,
            ]);

            // Perbarui atau buat kuota fakultas baru
            foreach ($request->faculty_quotas as $facultyId => $quota) {
                $facultyQuota = FacultyQuota::where('location_id', $location->id)
                    ->where('faculty_id', $facultyId)
                    ->first();

                if ($quota > 0) {
                    if ($facultyQuota) {
                        $facultyQuota->update(['quota' => $quota]);
                    } else {
                        FacultyQuota::create([
                            'location_id' => $location->id,
                            'faculty_id' => $facultyId,
                            'quota' => $quota,
                        ]);
                    }
                } elseif ($facultyQuota) {
                    // Jika kuota diatur ke 0 dan kuota sudah ada, hapus kuota
                    // Cek dulu apakah ada mahasiswa dari fakultas ini yang sudah terdaftar
                    $registeredCount = User::where('location_id', $location->id)
                        ->where('faculty_id', $facultyId)
                        ->count();

                    if ($registeredCount > 0) {
                        throw new \Exception("Tidak dapat menghapus kuota fakultas karena sudah ada {$registeredCount} mahasiswa yang terdaftar.");
                    }

                    $facultyQuota->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.locations.index')
                ->with('success', 'Lokasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified location from database.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location)
    {
        // Cek apakah ada mahasiswa yang terdaftar di lokasi ini
        $registeredCount = User::where('location_id', $location->id)->count();

        if ($registeredCount > 0) {
            return redirect()->route('admin.locations.index')
                ->with('error', "Tidak dapat menghapus lokasi karena sudah ada {$registeredCount} mahasiswa yang terdaftar.");
        }

        DB::beginTransaction();

        try {
            // Hapus semua kuota fakultas terkait
            FacultyQuota::where('location_id', $location->id)->delete();

            // Hapus lokasi
            $location->delete();

            DB::commit();

            return redirect()->route('admin.locations.index')
                ->with('success', 'Lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.locations.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
