<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MVS & Partners | Petty Cash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mvs-gold { color: #c5a043; }
        .bg-mvs-gold { background-color: #c5a043; }
    </style>
</head>
<body class="antialiased bg-[#050505] text-white">
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-[#c5a043]/5 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-[#c5a043]/5 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 max-w-5xl px-6 text-center">
            
            <div class="mb-12">
                <h2 class="text-3xl font-extrabold tracking-tighter italic text-white uppercase">
                    MVS <span class="mvs-gold">& PARTNERS</span>
                </h2>
            </div>

            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#c5a043] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c5a043]"></span>
                </span>
                <span class="text-[#c5a043] font-bold text-[10px] uppercase tracking-[0.4em]">Corporate Finance Portal</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-extrabold tracking-tighter mb-8 leading-[0.85] uppercase">
                Petty Cash <br/> 
                <span class="mvs-gold">Management.</span>
            </h1>
            
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto mb-12 font-medium opacity-80">
                Streamlining high-security financial disbursements and vault audit logs for Riyadh Headquarters.
            </p>

            <div class="flex justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-12 py-5 bg-white text-black font-extrabold rounded-2xl uppercase text-xs tracking-[0.2em] transition-all hover:bg-slate-200 shadow-2xl active:scale-95">
                        Enter System
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-16 py-6 bg-mvs-gold text-white font-extrabold rounded-2xl uppercase text-xs tracking-[0.2em] transition-all hover:brightness-110 shadow-[0_20px_50px_rgba(197,160,67,0.25)] active:scale-95">
                        Authorized Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>