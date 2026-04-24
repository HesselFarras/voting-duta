<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duta Terfavorit Pendidikan Fisika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        physRed: '#7D161A',
                        physGold: '#D4AF37',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-physRed text-white selection:bg-physGold selection:text-black font-sans">

    {{-- Logika Penentuan Waktu --}}
    @php
        $targetDate = \Carbon\Carbon::create(2026, 5, 3, 23, 59, 59);
        $isClosed = \Carbon\Carbon::now()->greaterThan($targetDate);
    @endphp

    <nav class="fixed top-0 right-0 p-6 md:p-8 z-50">
        @auth
            <div class="flex items-center space-x-4 bg-black/40 backdrop-blur-xl px-5 py-2.5 rounded-full border border-white/10 shadow-2xl">
                <img src="{{ Auth::user()->avatar }}" class="w-8 h-8 rounded-full border border-physGold/50" alt="avatar">
                <div class="hidden md:block text-right">
                    <p class="text-[10px] text-white/50 uppercase tracking-widest leading-none mb-1 text-right">Logged in as</p>
                    <p class="text-xs font-bold tracking-wider leading-none">{{ Auth::user()->name }}</p>
                </div>
                
                {{-- Hanya tampilkan Logout jika NIM belum diisi --}}
                @if(!Auth::user()->nim)
                    <div class="h-4 w-[1px] bg-white/20 mx-2"></div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-[10px] uppercase tracking-widest text-physGold hover:text-white transition-colors font-bold">Logout</button>
                    </form>
                @endif
            </div>
        @else
            {{-- Tombol Login hilang jika device sudah terkunci cookie --}}
            @if(!request()->cookie('device_locked'))
                <a href="/auth/google" class="group relative inline-flex items-center space-x-3 bg-physGold text-black px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-[0.3em] hover:bg-white transition-all duration-500 shadow-2xl shadow-physGold/20">
                    <span>Login Google</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h4.74c-.2 1.06-1.2 3.12-4.74 3.12-3.07 0-5.57-2.54-5.57-5.68s2.5-5.68 5.57-5.68c1.75 0 2.92.74 3.59 1.39l2.59-2.5c-1.66-1.55-3.82-2.5-6.18-2.5C7.22 2.35 3 6.57 3 11.75s4.22 9.4 8.78 9.4c4.77 0 7.94-3.35 7.94-8.08 0-.54-.06-1-.16-1.42l-7.08.02z"/></svg>
                </a>
            @else
                <div class="bg-black/60 border border-white/10 px-6 py-3 rounded-full backdrop-blur-md">
                    <span class="text-[10px] uppercase font-bold text-physGold tracking-widest italic">Device Locked</span>
                </div>
            @endif
        @endauth
    </nav>

    <section class="min-h-screen flex flex-col justify-center px-10 md:px-24 relative overflow-hidden bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-physRed via-[#4D0E10] to-black/30">
        <div class="max-w-4xl relative z-10">
            <div class="inline-flex items-center space-x-2 border border-white/30 px-4 py-1 rounded-full mb-8 bg-white/5">
                <span class="text-physGold text-xs animate-pulse">✦</span>
                <span class="text-[10px] uppercase tracking-[0.2em] font-semibold">Pemilihan 2026</span>
            </div>

            <h1 class="font-serif text-6xl md:text-8xl leading-[0.9] mb-8 tracking-tighter">
                Duta <span class="text-physGold italic">Favorit</span> <br>
                Pendidikan <br>
                Fisika 2026
            </h1>

            <p class="text-white/80 max-w-lg mb-12 text-sm md:text-base leading-relaxed italic font-light">
                "Pilih wajah baru yang akan membawa suara mahasiswa ke panggung yang lebih besar. Satu suara, satu masa depan."
            </p>

            <div class="flex flex-wrap gap-12 items-center border-t border-white/20 pt-10">
                <div>
                    <span class="block text-[10px] uppercase tracking-[0.4em] text-white/50 mb-2">Total Suara</span>
                    <span class="text-3xl font-bold tracking-tighter">{{ \App\Models\Vote::count() }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase tracking-[0.4em] text-white/50 mb-2">Sisa Waktu</span>
                    <div id="timer" class="text-3xl font-bold tracking-tighter text-physGold font-serif">
                        @if($isClosed) VOTING CLOSED @else 00:00:00:00 @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="vote-section" class="relative py-24 px-6 md:px-24 bg-gradient-to-b from-transparent to-black/50">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-20 text-center md:text-left">
                <h2 class="text-4xl md:text-6xl font-serif text-white mb-4 tracking-tight leading-none">
                    Daftar <span class="text-physGold italic">Kandidat</span>
                </h2>
                <div class="h-1 w-20 bg-physGold mx-auto md:mx-0 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 md:gap-14">
                @foreach($candidates as $candidate)
                <div class="relative group">
                    <div class="relative bg-black/30 border border-white/10 rounded-[2.5rem] overflow-hidden backdrop-blur-md transition-all duration-500 group-hover:-translate-y-2 group-hover:border-physGold/30 shadow-2xl">
                        
                        <div class="h-[460px] relative overflow-hidden bg-black/20">
                            @if($candidate->photo)
                                <img src="{{ $candidate->photo }}" class="w-full h-full object-cover opacity-90 transition-opacity duration-500" alt="{{ $candidate->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white/10">
                                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                            @endif

                            <div class="absolute bottom-6 right-6 bg-physGold text-black px-4 py-1 rounded-full font-black text-[10px] tracking-widest shadow-lg">
                                {{ $candidate->votes_count ?? 0 }} SUARA
                            </div>
                        </div>

                        <div class="p-10 text-center">
                            <h3 class="text-2xl font-serif text-white mb-2 tracking-tight">{{ $candidate->name }}</h3>
                            <p class="text-[10px] tracking-[0.4em] text-white/40 uppercase font-black mb-2">NIM: {{ $candidate->nim }}</p>
                            <p class="text-[9px] tracking-[0.2em] text-physGold/60 uppercase mb-8">Angkatan {{ $candidate->angkatan }}</p>

                            @auth
                                @if($isClosed)
                                    <div class="w-full py-4 rounded-xl border border-physGold/20 bg-black/40 text-center">
                                        <span class="text-[10px] uppercase tracking-[0.5em] text-physGold font-black">Voting Berakhir</span>
                                    </div>
                                @elseif(Auth::user()->vote)
                                    <div class="w-full py-4 rounded-xl border border-white/10 bg-white/5 text-center">
                                        <span class="text-[10px] uppercase tracking-[0.5em] text-white/20 font-black">Suara Terkunci</span>
                                    </div>
                                @else
                                    <form action="{{ url('/vote/'.$candidate->id) }}" method="POST">
                                        @csrf
                                        <button type="button" 
                                                data-name="{{ $candidate->name }}"
                                                class="btn-vote w-full bg-physGold text-black py-4 rounded-xl font-black text-[10px] uppercase tracking-[0.3em] hover:bg-white transition-all duration-300">
                                            Pilih Sekarang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="/auth/google" class="w-full block border border-physGold/50 text-physGold py-4 rounded-xl text-[10px] uppercase tracking-[0.4em] font-black hover:bg-physGold hover:text-black text-center transition-all duration-300">
                                    Login to Vote
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-24 px-6 md:px-24 bg-black/20 backdrop-blur-sm">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-serif text-white mb-4 italic">Live <span class="text-physGold">Leaderboard</span></h2>
                <div class="h-1 w-20 bg-physGold mx-auto rounded-full"></div>
            </div>
            <div class="bg-black/30 p-4 md:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl h-[350px] md:h-[450px]">
                <canvas id="leaderboardChart"></canvas>
            </div>
        </div>
    </section>

    <footer class="py-20 text-center bg-black/40 border-t border-white/10">
        <p class="text-white/20 text-[10px] uppercase tracking-[0.5em] font-bold mb-2">&copy; 2026 Pendidikan Fisika</p>
        <span class="text-white/20 uppercase tracking-[0.5em] text-[10px]">Created by <a href="https://instagram.com/binatangsudahjinak" target="_blank" class="text-white/20 underline hover:text-physGold">@binatangsudahjinak</a></span>
    </footer>

    {{-- MODAL VERIFIKASI NIM --}}
    @auth
        @if(!Auth::user()->nim)
        <div class="fixed inset-0 bg-black/95 backdrop-blur-2xl z-[100] flex items-center justify-center p-6 text-center">
            <div class="max-w-md w-full">
                <h2 class="font-serif text-4xl text-physGold italic mb-4">Verifikasi NIM</h2>
                <div class="h-[1px] w-12 bg-physGold mx-auto mb-6"></div>
                <p class="text-white/40 text-[10px] uppercase tracking-[0.3em] mb-10">Gunakan NIM Pendidikan Fisika Anda</p>
                
                <form id="nimForm" action="{{ route('verify.nim') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="text" id="nimInput" name="nim" placeholder="2280XXXXXXXX" required
                        class="w-full bg-transparent border-b-2 border-white/10 focus:border-physGold py-4 text-center text-3xl outline-none transition-all font-bold tracking-widest text-white uppercase placeholder:text-white/5">
                    
                    <button type="submit" class="w-full bg-physGold text-black py-5 rounded-2xl font-black text-[10px] uppercase tracking-[0.4em] hover:bg-white transition-all shadow-2xl shadow-physGold/20">
                        Konfirmasi Identitas
                    </button>
                </form>
            </div>
        </div>
        @endif
    @endauth

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. CHART LOGIC
        const ctx = document.getElementById('leaderboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($candidates->pluck('name')) !!},
                datasets: [{
                    label: 'Perolehan Suara',
                    data: {!! json_encode($candidates->pluck('votes_count')) !!},
                    backgroundColor: '#D4AF37',
                    borderColor: '#D4AF37',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleFont: { family: 'Inter', size: 14, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 13 },
                        padding: 12,
                        displayColors: false,
                        borderColor: '#D4AF37',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Suara';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }, 
                        ticks: { color: 'rgba(255, 255, 255, 0.5)', font: { size: 10 } } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { display: false } 
                    }
                }
            }
        });

        // 2. VOTE LOGIC
        document.querySelectorAll('.btn-vote').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const candidateName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Konfirmasi Pilihan',
                    text: `Yakin ingin memilih ${candidateName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#D4AF37',
                    cancelButtonColor: '#7D161A',
                    confirmButtonText: 'Ya, Pilih!',
                    cancelButtonText: 'Batal',
                    background: '#1a1a1a',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => { Swal.showLoading() },
                            background: '#1a1a1a',
                            color: '#ffffff'
                        });
                        form.submit();
                    }
                });
            });
        });

        // 3. NIM VALIDATION LOGIC (NEW)
        const nimForm = document.querySelector('form[action="{{ route("verify.nim") }}"]');
        if (nimForm) {
            nimForm.addEventListener('submit', function(e) {
                const nimInput = this.querySelector('input[name="nim"]');
                const nimValue = nimInput.value.trim();

                // Cek apakah diawali 2280
                if (!nimValue.startsWith('2280')) {
                    e.preventDefault(); // Stop form submit

                    Swal.fire({
                        icon: 'error',
                        title: 'NIM Tidak Valid!',
                        text: 'Maaf, voting ini khusus mahasiswa Pendidikan Fisika.',
                        background: '#1a1a1a',
                        color: '#ffffff',
                        confirmButtonColor: '#7D161A'
                    });
                }
            });
        }

        // 4. TIMER LOGIC
        function updateTimer() {
            const targetDate = new Date("May 3, 2026 14:59:59").getTime();
            const now = new Date().getTime();
            const diff = targetDate - now;
            
            if (diff <= 0) { 
                document.getElementById('timer').innerHTML = "VOTING CLOSED"; 
                return; 
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('timer').innerHTML = `${days}d : ${hours}h : ${mins}m : ${secs < 10 ? '0'+secs : secs}s`;
        }
        setInterval(updateTimer, 1000);
        updateTimer();
    });
</script>

    {{-- Session Notifications --}}
    @if(session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", background: '#1a1a1a', color: '#ffffff', confirmButtonColor: '#D4AF37' });</script>
    @endif
    @if(session('error'))
        <script>Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", background: '#1a1a1a', color: '#ffffff', confirmButtonColor: '#7D161A' });</script>
    @endif

</body>
</html>