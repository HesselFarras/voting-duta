<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store($candidateId)
    {
        // 1. Cek dulu, user sudah login belum?
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Login dulu bro pakai akun Google!');
        }

        $user = Auth::user();

        // 2. Cek di database, user ini sudah pernah vote belum?
        // Kita pakai relasi 'vote' yang kita buat di Model User tadi
        if ($user->vote) {
            return redirect()->back()->with('error', 'Cukup satu kali saja, jangan serakah ya!');
        }

        // 3. Kalau lolos cek, baru simpan suaranya
        Vote::create([
            'user_id' => $user->id,
            'candidate_id' => $candidateId,
        ]);

        return redirect()->back()->with('success', 'Mantap! Suara kamu sudah masuk.');
    }
}