<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    /**
     * Display a listing of the faculties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $faculties = Faculty::withCount('users')->get();

        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new faculty.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.faculties.create');
    }

    /**
     * Store a newly created faculty in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:faculties,code',
        ]);

        Faculty::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    /**
     * Display the specified faculty.
     *
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\View\View
     */
    public function show(Faculty $faculty)
    {
        $faculty->loadCount('users');
        $users = User::where('faculty_id', $faculty->id)
            ->with('location')
            ->paginate(15);

        return view('admin.faculties.show', compact('faculty', 'users'));
    }

    /**
     * Show the form for editing the specified faculty.
     *
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\View\View
     */
    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified faculty in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:faculties,code,' . $faculty->id,
        ]);

        $faculty->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    /**
     * Remove the specified faculty from database.
     *
     * @param  \App\Models\Faculty  $faculty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Faculty $faculty)
    {
        // Cek apakah ada mahasiswa yang terkait dengan fakultas ini
        $userCount = User::where('faculty_id', $faculty->id)->count();

        if ($userCount > 0) {
            return redirect()->route('admin.faculties.index')
                ->with('error', "Tidak dapat menghapus fakultas karena masih ada {$userCount} mahasiswa yang terdaftar dari fakultas ini.");
        }

        // Cek apakah ada kuota fakultas yang terkait
        $hasQuotas = $faculty->facultyQuotas()->exists();

        if ($hasQuotas) {
            return redirect()->route('admin.faculties.index')
                ->with('error', 'Tidak dapat menghapus fakultas karena masih ada kuota yang terkait dengan fakultas ini di beberapa lokasi KKN.');
        }

        $faculty->delete();

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }
}
