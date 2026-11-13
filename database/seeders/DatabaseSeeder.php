<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach ([
            ['name' => 'Alya Putri', 'nim' => '22800001', 'angkatan' => '2023'],
            ['name' => 'Bima Pratama', 'nim' => '22800002', 'angkatan' => '2023'],
            ['name' => 'Citra Lestari', 'nim' => '22800003', 'angkatan' => '2024'],
            ['name' => 'Daffa Ramadhan', 'nim' => '22800004', 'angkatan' => '2024'],
        ] as $candidate) {
            Candidate::updateOrCreate(['nim' => $candidate['nim']], $candidate);
        }

        foreach (['22801234', '22805678', '22809999'] as $nim) {
            DB::table('voters_whitelist')->updateOrInsert(
                ['nim' => $nim],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
