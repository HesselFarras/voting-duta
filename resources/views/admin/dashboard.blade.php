<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Duta Fisika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { physGold: '#D4AF37' } } } }
    </script>
</head>
<body class="bg-[#0A0A0A] text-white p-6 md:p-10 font-sans min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-end mb-12 border-b border-white/10 pb-8">
            <h1 class="text-4xl font-bold">Admin <span class="text-physGold">Dashboard</span></h1>
            <a href="/" class="text-[10px] uppercase tracking-widest text-white/40 hover:text-white transition-all">Back to Website</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <div class="bg-white/5 p-8 rounded-[2.5rem] border border-white/10 sticky top-10">
                    <h2 class="text-xl text-physGold mb-6">Update Foto Kandidat</h2>
                    <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')
                        
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-white/30 mb-2 block ml-1">Kandidat Terpilih</label>
                            <input type="text" id="display_name" readonly 
                                class="w-full p-4 bg-black/20 rounded-2xl border border-white/5 text-sm text-white/40 outline-none" 
                                placeholder="Pilih dari daftar kandaidat...">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-white/30 mb-2 block ml-1">Pilih File Foto Baru</label>
                            <input type="file" name="photo" id="photo_input" accept="image/png, image/jpeg, image/jpg, image/webp" disabled required
                                class="w-full p-3 bg-black/20 rounded-2xl border border-white/5 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:bg-physGold file:text-black hover:file:bg-white transition-all">
                        </div>

                        <button type="submit" id="btnUpdate" disabled 
                            class="w-full bg-white/10 text-white/20 py-5 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all cursor-not-allowed">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-4">
                <h2 class="text-xl italic text-white/60 mb-4 font-serif">Daftar Kandidat & Suara</h2>
                @foreach($candidates as $c)
                <div class="flex items-center justify-between p-4 bg-black/20 rounded-3xl border border-white/5 group hover:border-physGold/30 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 bg-black">
                            <img src="{{ $c->photo }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-base">{{ $c->name }}</p>
                            <p class="text-[10px] opacity-40 uppercase tracking-widest font-mono">Suara: {{ $c->votes_count }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button" 
                            class="btn-edit-trigger p-3 px-5 bg-physGold/10 text-physGold hover:bg-physGold hover:text-black rounded-xl text-[10px] font-bold uppercase transition-all"
                            data-id="{{ $c->id }}" 
                            data-name="{{ $c->name }}">
                            Edit Foto
                        </button>

                        <!-- <form action="{{ url('/admin/candidate/reset/'.$c->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/5 hover:bg-gray-600 text-white rounded-xl text-[10px] font-bold uppercase transition-all">
                                Reset Foto
                            </button>
                        </form> -->
                        
                        <!-- <form action="{{ url('/admin/candidate/'.$c->id) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete p-3 bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white rounded-xl text-[10px] font-bold uppercase transition-all">
                                Hapus
                            </button>
                        </form> -->
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editForm');
    const btnUpdate = document.getElementById('btnUpdate');
    const photoInput = document.getElementById('photo_input');
    const displayName = document.getElementById('display_name');

    // 1. Handle klik tombol edit
    document.querySelectorAll('.btn-edit-trigger').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            // Set data ke form
            editForm.action = "/admin/candidate/" + id;
            displayName.value = name;
            
            // Aktifkan input file
            photoInput.disabled = false;
            
            // Reset button state (biar harus pilih file dulu)
            btnUpdate.disabled = true;
            btnUpdate.className = "w-full bg-white/10 text-white/20 py-5 rounded-2xl font-bold text-[10px] uppercase tracking-widest cursor-not-allowed";
            btnUpdate.innerText = "Pilih Foto Baru";

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    // 2. Aktifkan tombol cuma pas file terpilih
    photoInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            btnUpdate.disabled = false;
            btnUpdate.className = "w-full bg-physGold text-black py-5 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-white shadow-lg shadow-physGold/20 transition-all cursor-pointer";
            btnUpdate.innerText = "Simpan Perubahan";
        }
    });

    // 3. Handle loading pas submit
    editForm.addEventListener('submit', function() {
        btnUpdate.disabled = true;
        btnUpdate.innerText = "MEMPROSES UPLOAD...";
        btnUpdate.classList.add('animate-pulse');
    });

    // 4. SweetAlert buat Konfirmasi Hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus Kandidat?',
                text: "Data kandidat dan seluruh suara yang masuk akan hilang permanen dari storage & database!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#333',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1a1a1a',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        background: '#1a1a1a',
                        color: '#fff',
                        didOpen: () => Swal.showLoading()
                    });
                    form.submit();
                }
            });
        });
    });
});
</script>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", background: '#1a1a1a', color: '#fff', confirmButtonColor: '#D4AF37' });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", background: '#1a1a1a', color: '#fff', confirmButtonColor: '#ef4444' });
        </script>
    @endif
</body>
</html>