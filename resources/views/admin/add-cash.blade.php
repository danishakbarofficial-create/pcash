<x-app-layout>
    <style>
        /* Custom Styling for Dark Inputs */
        .mvs-input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 12px !important;
            transition: all 0.3s ease;
        }
        .mvs-input:focus {
            border-color: #c5a043 !important;
            box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.1) !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c5a043; border-radius: 10px; }

        /* Professional Receipt Button */
        .btn-view-receipt {
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #c5a043;
            border: 1px solid rgba(197, 160, 67, 0.3);
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-view-receipt:hover {
            background: #c5a043;
            color: #000;
            transform: translateY(-1px);
        }
    </style>

    <div class="py-10 bg-[#0b0c10] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex justify-between items-end mb-10 border-b border-white/5 pb-6">
                <div>
                    <h2 class="font-black text-3xl text-white italic uppercase tracking-tighter">
                        Vault <span class="text-[#c5a043]">In-Flow</span>
                    </h2>
                    <p class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-1">Master Liquidity Management</p>
                </div>
                <a href="{{ route('dashboard') }}" class="group flex items-center text-[10px] font-black text-[#c5a043] bg-[#c5a043]/5 border border-[#c5a043]/20 px-5 py-2.5 rounded-xl hover:bg-[#c5a043] hover:text-black transition-all uppercase tracking-widest">
                    <span class="mr-2 group-hover:-translate-x-1 transition-transform">←</span> Dashboard
                </a>
            </div>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-bold rounded-2xl flex items-center animate-pulse">
                    <span class="mr-3">✅</span> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- LEFT SIDE: DEPOSIT FORM --}}
                <div class="lg:col-span-4">
                    <div class="bg-[#11141b] rounded-3xl p-8 border border-white/5 shadow-2xl sticky top-24">
                        <div class="mb-8 flex items-center gap-4">
                            <div class="bg-[#c5a043]/10 p-3 rounded-2xl">
                                <span class="text-xl">💰</span>
                            </div>
                            <div>
                                <h3 class="font-black text-white uppercase tracking-widest text-sm">New Deposit</h3>
                                <p class="text-slate-500 text-[9px] font-bold uppercase tracking-tight">Add cash to central vault</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.storeCash') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-500 block mb-2 tracking-widest italic">Deposit Date</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                       class="mvs-input w-full text-sm font-bold scheme-dark">
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-500 block mb-2 tracking-widest italic">Source (Bank/HO)</label>
                                <input type="text" name="source" placeholder="e.g. Al Rajhi Bank" required 
                                       class="mvs-input w-full text-sm font-bold placeholder:text-slate-700">
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-500 block mb-2 tracking-widest italic">Amount (SAR)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#c5a043] font-black text-xs">SAR</span>
                                    <input type="number" step="0.01" name="amount" placeholder="0.00" required 
                                           class="mvs-input w-full pl-12 text-xl font-black text-white focus:ring-[#c5a043]">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-500 block mb-2 tracking-widest italic">Proof Receipt</label>
                                <div class="border-2 border-dashed border-white/5 rounded-2xl p-4 bg-white/[0.02] hover:border-[#c5a043]/30 transition-colors group cursor-pointer">
                                    <input type="file" name="proof" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:bg-[#c5a043] file:text-black cursor-pointer">
                                </div>
                                <p class="text-[8px] text-slate-600 mt-2 uppercase font-bold tracking-tighter">* Upload Bank Slip or Transfer Screenshot</p>
                            </div>

                            <button type="submit" class="w-full bg-[#c5a043] text-black font-black py-4 rounded-2xl uppercase text-[11px] tracking-[0.2em] hover:bg-[#d4b35a] transition-all shadow-lg shadow-[#c5a043]/10 active:scale-[0.98]">
                                Confirm & Deposit
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT SIDE: LOGS HISTORY --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- INFLOW TABLE --}}
                    <div class="bg-[#11141b] rounded-3xl border border-white/5 overflow-hidden shadow-2xl">
                        <div class="p-6 bg-white/[0.02] border-b border-white/5 flex justify-between items-center">
                            <h3 class="font-black text-white text-[11px] uppercase tracking-[0.2em] flex items-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></span>
                                Deposit History
                            </h3>
                            <span class="text-[9px] bg-emerald-500/10 text-emerald-500 px-3 py-1 rounded-full font-black uppercase border border-emerald-500/20">Vault In-Flow</span>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left">
                                <thead class="bg-black/40 text-[#c5a043] uppercase font-black text-[9px] tracking-widest">
                                    <tr>
                                        <th class="p-5">Date</th>
                                        <th class="p-5">Source Detail</th>
                                        <th class="p-5 text-right">Credit Amount</th>
                                        <th class="p-5 text-center">Receipt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @php $inflows = \App\Models\VaultLog::where('type', 'deposit')->latest()->take(10)->get(); @endphp
                                    @forelse($inflows as $log)
                                        <tr class="hover:bg-white/[0.02] transition-colors group">
                                            <td class="p-5 text-slate-500 font-bold text-[10px] uppercase">
                                                {{ \Carbon\Carbon::parse($log->date)->format('d M, Y') }}
                                            </td>
                                            <td class="p-5">
                                                <div class="font-black text-white text-xs uppercase tracking-tight group-hover:text-[#c5a043] transition-colors">
                                                    {{ $log->source }}
                                                </div>
                                                <div class="text-[8px] text-slate-600 font-bold uppercase">Transaction Logged</div>
                                            </td>
                                            <td class="p-5 text-right text-emerald-500 font-black text-sm italic">
                                                <span class="text-[9px] opacity-50 mr-1 not-italic">SAR</span>{{ number_format($log->amount, 2) }}
                                            </td>
                                            <td class="p-5 text-center">
                                                @if($log->proof)
                                                    <a href="{{ asset('storage/' . $log->proof) }}" target="_blank" class="btn-view-receipt">
                                                        View Receipt
                                                    </a>
                                                @else 
                                                    <span class="text-slate-800 text-[8px] font-black uppercase tracking-tighter">No File</span> 
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-16 text-center text-slate-600 uppercase text-[10px] font-black tracking-widest italic">No deposits recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- OUTFLOW TABLE (Recent Assignments & Expenses) --}}
                    {{-- OUTFLOW TABLE (ONLY Recent Assignments) --}}
<div class="bg-[#11141b] rounded-3xl border border-white/5 overflow-hidden shadow-xl opacity-80 hover:opacity-100 transition-all">
    <div class="p-5 bg-black/40 border-b border-white/5 flex justify-between items-center">
        <h3 class="font-black text-rose-500 text-[10px] uppercase tracking-widest">Recent Cash Out (To Staff)</h3>
        <a href="{{ route('admin.assignList') }}" class="text-[9px] font-black text-[#c5a043] hover:underline tracking-tighter italic">VIEW ALL LOGS →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <tbody class="divide-y divide-white/5">
                @php 
                    // Agar Controller se $recentCashOut aa raha hai to wo use karega, 
                    // warna khud filter karega (Security backup)
                    $displayOutflows = isset($recentCashOut) ? $recentCashOut : \App\Models\Transaction::with('user')
                        ->where('type', 'assignment')
                        ->where('status', 'approved')
                        ->latest()
                        ->take(5)
                        ->get(); 
                @endphp
                
                @forelse($displayOutflows as $log)
                    <tr class="hover:bg-rose-500/[0.02] transition">
                        <td class="p-4 text-slate-600 text-[9px] font-black">
                            {{ \Carbon\Carbon::parse($log->transaction_date)->format('d M') }}
                        </td>
                        <td class="p-4">
                            <div class="font-black text-slate-300 text-[10px] uppercase">
                                {{ $log->user->name ?? 'SYSTEM' }}
                            </div>
                            <div class="text-[8px] font-bold text-slate-600 italic uppercase">
                                Wallet Assignment
                            </div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="text-rose-600 font-black text-xs italic">
                                - SAR {{ number_format($log->amount, 2) }}
                            </div>
                            
                            <div class="mt-1">
                                @if($log->receipt_path)
                                    <a href="{{ asset('storage/' . $log->receipt_path) }}" target="_blank" class="btn-view-receipt">
                                        View Receipt
                                    </a>
                                @else
                                    <span class="text-[7px] text-slate-800 font-bold uppercase">No File</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-10 text-center text-slate-700 uppercase text-[9px] font-black italic">
                            No recent assignments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>