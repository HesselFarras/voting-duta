<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Tambahin ini biar ngetiknya lebih pendek

class VoteController extends Controller
{
    public function store($candidateId)
    {
        // 1. CEK WAKTU (Harus paling atas!)
        $targetDate = Carbon::create(2026, 5, 3, 23, 59, 59);
        if (Carbon::now()->greaterThan($targetDate)) {
            return redirect()->back()->with('error', 'Mohon maaf, waktu voting telah resmi berakhir!');
        }

        // 2. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Login dulu bro pakai akun Google!');
        }

        $user = Auth::user();

        // 3. Cek apakah user sudah pernah vote?
        if ($user->vote) {
            return redirect()->back()->with('error', 'Cukup satu kali saja, suara kamu sudah terkunci!');
        }

        // 4. Simpan suaranya
        try {
            Vote::create([
                'user_id' => $user->id,
                'candidate_id' => $candidateId,
            ]);

            return redirect()->back()->with('success', 'Mantap! Suara kamu sudah berhasil masuk.');
        } catch (\Exception $e) {
            // Jaga-jaga kalau ada error database (misal Supabase lagi down)
            return redirect()->back()->with('error', 'Waduh, ada gangguan teknis. Coba lagi ya!');
        }
    }
}