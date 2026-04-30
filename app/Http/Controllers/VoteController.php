<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoteController extends Controller
{
    /**
     * Fungsi untuk verifikasi NIM (Whitelist + Device Lock)
     */
    public function index()
    {
        // Menginstruksikan browser/Vercel untuk tidak menyimpan cache
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        $candidates = \App\Models\Candidate::withCount('votes')->orderBy('id', 'asc')->get();
        return view('welcome', compact('candidates'));
    }
    public function verifyNim(Request $request)
    {
        // 1. Validasi awal: Harus ada dan harus diawali 2280
        $request->validate([
            'nim' => ['required', 'string', 'regex:/^2280/']
        ]);

        $inputNim = trim($request->nim);

        // 2. Cek apakah NIM ada di daftar Whitelist (Tabel dari Excel panitia)
        $isWhitelisted = DB::table('voters_whitelist')->where('nim', $inputNim)->exists();
        
        if (!$isWhitelisted) {
            return redirect()->back()->with('error', 'NIM kamu tidak terdaftar di database Pendidikan Fisika!');
        }

        // 3. Cek apakah NIM sudah diklaim oleh akun Google lain
        $alreadyClaimed = User::where('nim', $inputNim)->exists();
        if ($alreadyClaimed) {
            return redirect()->back()->with('error', 'NIM ini sudah diverifikasi oleh akun Google lain!');
        }

        // 4. Simpan NIM ke User yang sedang login
        $user = Auth::user();
        $user->nim = $inputNim;
        $user->save();

        // 5. Gembok Device (Cookie) biar nggak bisa ganti akun di browser ini
        $cookie = cookie()->forever('device_locked', 'true');

        return redirect('/')->with('success', 'Identitas terverifikasi! Selamat memilih.')->withCookie($cookie);
    }

    /**
     * Fungsi untuk menyimpan suara
     */
    public function store($candidateId)
    {
        // 1. CEK WAKTU (Set ke Jakarta biar nggak selisih sama server)
        $targetDate = Carbon::create(2026, 5, 2, 11, 59, 59, 'Asia/Jakarta');
        if (Carbon::now('Asia/Jakarta')->greaterThan($targetDate)) {
            return redirect()->back()->with('error', 'Mohon maaf, waktu voting telah resmi berakhir!');
        }

        // 2. Cek login
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Login dulu bro pakai akun Google!');
        }

        $user = Auth::user();

        // 3. CEK NIM (Security Layer)
        // Kalau user belum punya NIM di database, dia nggak boleh vote
        if (!$user->nim) {
            return redirect()->back()->with('error', 'Verifikasi NIM kamu dulu sebelum memberikan suara!');
        }

        // 4. Cek double vote
        if ($user->vote) {
            return redirect()->back()->with('error', 'Cukup satu kali saja, suara kamu sudah terkunci!');
        }

        // 5. Simpan suaranya
        try {
            Vote::create([
                'user_id' => $user->id,
                'candidate_id' => $candidateId,
            ]);

            return redirect()->back()->with('success', 'Mantap! Suara kamu sudah berhasil masuk.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Waduh, ada gangguan teknis. Coba lagi ya!');
        }
    }
}