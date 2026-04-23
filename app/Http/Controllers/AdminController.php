<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index()
    {
        // List email yang boleh jadi admin
        $adminEmails = [
            'hesselfaras39@gmail.com', // Email lo
            'hesselfarras1@gmail.com',      // Email temen lo
            'firazn06@gmail.com'   // Tambahin terus ke bawah...
        ];

        // Cek apakah email user yang login ada di daftar di atas
        if (!in_array(Auth::user()->email, $adminEmails)) {
            abort(403, 'Lau siape pruy???');
        }

        $candidates = Candidate::withCount('votes')
                        ->orderBy('id', 'asc')
                        ->get();

        return view('admin.dashboard', compact('candidates'));
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        // 1. Update Validasi: Tambahin format lain di sini
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ], [
            'photo.required' => 'Pilih foto dulu bos!',
            'photo.mimes'    => 'Format harus JPG, PNG, atau WEBP ya.',
            'photo.max'      => 'Maksimal 2MB aja.'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'candidate_' . $id . '_' . time() . '.' . $extension;
            $bucketName = 'photos';
            
            $supabaseUploadUrl = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/$bucketName/$fileName";

            try {
                $fileContent = file_get_contents($file->getRealPath());

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    // 2. Dinamis: Ambil Mime Type asli filenya, jangan di-hardcode
                    'Content-Type'  => $file->getMimeType(), 
                    'x-upsert'      => 'true'
                ])->withBody($fileContent, $file->getMimeType())
                ->post($supabaseUploadUrl);

                if ($response->successful()) {
                    $publicUrl = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/public/$bucketName/$fileName";
                    $candidate->photo = $publicUrl;
                } else {
                    return redirect()->back()->with('error', 'Supabase Error: ' . $response->body());
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal Upload: ' . $e->getMessage());
            }
        }

        $candidate->save();
        return redirect()->back()->with('success', 'Update Berhasil');
    }

    public function resetPhoto($id)
    {
        $candidate = Candidate::findOrFail($id);
        $bucketName = 'photos';

        // 1. Hapus foto lama di Supabase (supaya gak nyampah)
        if ($candidate->photo && !str_contains($candidate->photo, 'default.png')) {
            $urlParts = explode('/', $candidate->photo);
            $fileName = end($urlParts);
            
            Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
            ])->delete(env('SUPABASE_URL') . "/storage/v1/object/$bucketName/$fileName");
        }

        // 2. Set balik ke URL default
        $candidate->photo = env('SUPABASE_URL') . "/storage/v1/object/public/$bucketName/default.png";
        $candidate->save();

        return redirect()->back()->with('success', 'Foto kandidat berhasil di-reset ke default!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Logic: Hapus foto di Storage dulu sebelum hapus data di Database
        if ($candidate->photo) {
            $urlParts = explode('/', $candidate->photo);
            $fileName = end($urlParts);
            $bucketName = 'photos';
            
            try {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                ])->delete(env('SUPABASE_URL') . "/storage/v1/object/$bucketName/$fileName");
            } catch (\Exception $e) {
                // Biarkan lanjut hapus DB kalaupun storage gagal
            }
        }

        $candidate->delete();
        return redirect()->back()->with('success', 'Kandidat dan fotonya berhasil dihapus!');
    }
}