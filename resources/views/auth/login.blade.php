<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MVS & Partners | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mvs-gold { color: #c5a043; }
        .bg-mvs-gold { background-color: #c5a043; }
    </style>
</head>
<body class="antialiased bg-[#0a0a0a]">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="mb-6 text-center">
            <img src="{{ asset('images/mvs-logo.png') }}" class="h-24 w-auto mx-auto mb-2" alt="MVS Logo">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.6em] ml-2">Internal Operations</p>
        </div>

        <div class="w-full sm:max-w-md mt-2 px-10 py-12 bg-[#111111] shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden sm:rounded-[32px] border border-white/5 relative">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-mvs-gold"></div>

            <div class="text-center mb-10">
                <h3 class="text-2xl font-[800] text-white tracking-tight uppercase italic">Authorized Access</h3>
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mt-1 font-bold">Staff Sign-In — Riyadh HQ</p>
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500 block mb-2 ml-1">Corporate Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full border-white/10 focus:border-mvs-gold focus:ring-0 rounded-2xl bg-white/5 text-white text-sm p-4 transition-all" />
                    @error('email') <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500 block mb-2 ml-1">Secret Password</label>
                    <input id="password" type="password" name="password" required 
                        class="w-full border-white/10 focus:border-mvs-gold focus:ring-0 rounded-2xl bg-white/5 text-white text-sm p-4 transition-all" />
                </div>

                <button type="submit" class="w-full bg-mvs-gold hover:brightness-110 text-white font-[800] py-5 rounded-2xl uppercase text-[11px] tracking-[0.3em] transition-all">
                    Secure Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>