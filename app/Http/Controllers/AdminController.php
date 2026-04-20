<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Ganti dengan email Google lo
        if (Auth::user()->email !== 'hesselfarras1@gmail.com') {
            abort(403, 'Akses ditolak!');
        }

        $candidates = Candidate::withCount('votes')->get();
        return view('admin.dashboard', compact('candidates'));
    }

    public function store(Request $request)
    {
        // Pastikan angkatan ikut kesimpan
        $data = $request->validate([
            'name' => 'required',
            'nim' => 'required',
            'angkatan' => 'required|numeric',
            'photo' => 'nullable',
        ]);

        \App\Models\Candidate::create($data);

        return redirect()->back()->with('success', 'Kandidat Berhasil Ditambah!');
    }
    public function destroy($id)
    {
        Candidate::destroy($id);
        return redirect()->back()->with('success', 'Kandidat dihapus!');
    }
}