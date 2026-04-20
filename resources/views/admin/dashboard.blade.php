<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel - Duta Fisika</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0F0F0F] text-white p-10 font-sans">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-10 border-b border-white/10 pb-5">
            <h1 class="text-3xl font-serif italic text-[#D4AF37]">Admin <span class="text-white">Dashboard</span></h1>
            <a href="/" class="text-xs uppercase tracking-widest text-white/50 hover:text-white">Back to Website</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 h-fit">
                <h2 class="text-lg font-bold mb-6 italic text-[#D4AF37]">Tambah Kandidat</h2>
                <form action="{{ url('/admin/candidate') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Lengkap" 
                        class="w-full p-3 bg-white/5 rounded-xl border border-white/10 text-sm outline-none focus:border-physGold text-white" required>
                    
                    <input type="text" name="nim" placeholder="NIM" 
                        class="w-full p-3 bg-white/5 rounded-xl border border-white/10 text-sm outline-none focus:border-physGold text-white" required>
                    
                    <input type="number" name="angkatan" placeholder="Angkatan (Contoh: 2023)" 
                        class="w-full p-3 bg-white/5 rounded-xl border border-white/10 text-sm outline-none focus:border-physGold text-white" required>
                    
                    <input type="text" name="photo" placeholder="URL Foto (Supabase Public Link)" 
                        class="w-full p-3 bg-white/5 rounded-xl border border-white/10 text-sm outline-none focus:border-physGold text-white">
                    
                    <button type="submit" class="w-full bg-physGold text-black py-4 rounded-xl font-black text-[10px] uppercase tracking-[0.3em] hover:bg-white transition duration-300 shadow-lg shadow-physGold/20">
                        Simpan Kandidat
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white/5 p-8 rounded-[2rem] border border-white/10">
                <h2 class="text-lg font-bold mb-6 italic text-[#D4AF37]">Monitoring Suara</h2>
                <div class="space-y-4">
                    @foreach($candidates as $c)
                    <div class="flex items-center justify-between p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <p class="font-bold">{{ $c->name }}</p>
                            <p class="text-[10px] text-white/40 uppercase tracking-widest">NIM: {{ $c->nim }}</p>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="text-right">
                                <p class="text-xl font-serif font-bold text-[#D4AF37]">{{ $c->votes_count }}</p>
                                <p class="text-[8px] uppercase tracking-tighter opacity-50">Votes</p>
                            </div>
                            <form action="{{ url('/admin/candidate/'.$c->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="p-2 hover:bg-red-500/20 rounded-lg group transition">
                                    <svg class="w-5 h-5 text-red-500/50 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>