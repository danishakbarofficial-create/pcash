<x-app-layout>
    <style>
        .mvs-input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 1rem !important;
        }
        .mvs-input:focus {
            border-color: #c5a043 !important;
            box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.2) !important;
            outline: none;
        }
        select.mvs-input option {
            background: #11141b;
            color: white;
        }
        .currency-input {
            padding-left: 3.5rem !important;
        }
    </style>

    <div class="py-12 bg-black min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Notifications Logic --}}
@if(session('error'))
    {{-- Pehle Error check karein --}}
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 font-bold rounded-2xl flex items-center italic">
        <span class="mr-3">⚠️</span> {{ session('error') }}
    </div>
@elseif(session('success'))
    {{-- Agar error nahi hai, tabhi success dikhaye --}}
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-bold rounded-2xl flex items-center italic">
        <span class="mr-3">✅</span> {{ session('success') }}
    </div>
@endif

            <div class="bg-[#11141b] overflow-hidden shadow-2xl rounded-[2.5rem] border border-white/5 relative">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-[#c5a043]/5 blur-[60px] rounded-full"></div>

                <div class="p-8 sm:p-12">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h2 class="font-black text-2xl text-white italic uppercase tracking-tighter">
                                Assign <span class="text-[#c5a043]">Staff Cash</span>
                            </h2>
                            <p class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-1">Vault Liquidity Outflow</p>
                        </div>
                    </div>

                    <div class="mb-10 bg-black/40 border border-white/5 p-6 rounded-2xl flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Available Vault Balance</p>
                            <h4 class="text-3xl font-black text-white italic">
                                <span class="text-[#c5a043] text-sm not-italic mr-1">SAR</span>{{ number_format($vault->total_balance ?? 0, 2) }}
                            </h4>
                        </div>
                        <div class="text-3xl opacity-50 text-[#c5a043]">🏛️</div>
                    </div>

                    <form action="{{ route('admin.assignCash') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-8">
                            
                            {{-- Select Staff --}}
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Select Recipient Staff</label>
                                <select name="user_id" required class="mvs-input w-full text-sm font-bold cursor-pointer">
                                    <option value="">Choose Staff Member...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ strtoupper($user->name) }} — (Wallet: SAR {{ number_format($user->cash_balance, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Select Project (Dynamic) --}}
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Assign to Project</label>
                                <select name="project_id" required class="mvs-input w-full text-sm font-bold cursor-pointer">
                                    <option value="">Choose Project...</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Amount to Assign (SAR)</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-4 z-10 text-[#c5a043] font-black text-xs italic pointer-events-none">SAR</span>
                                    <input type="number" step="0.01" name="amount" placeholder="0.00" required 
                                           class="mvs-input currency-input w-full text-xl font-black italic placeholder:text-slate-800">
                                </div>
                            </div>

                            {{-- Receipt --}}
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Signed Receipt (Proof)</label>
                                <div class="border-2 border-dashed border-white/5 rounded-2xl p-6 bg-white/[0.02] hover:border-[#c5a043]/30 transition-all text-center group">
                                    <input type="file" name="receiver_receipt" required 
                                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-[#c5a043] file:text-black cursor-pointer group-hover:file:bg-white transition-all">
                                </div>
                            </div>

                            <button type="submit" class="group w-full bg-[#c5a043] text-black font-black py-5 rounded-2xl uppercase text-[11px] tracking-[0.3em] hover:bg-white transition-all active:scale-95">
                                <span>Confirm Assignment</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>