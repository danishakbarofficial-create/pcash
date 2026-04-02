<x-app-layout>
    <style>
        .mvs-card {
            background: rgba(17, 20, 27, 0.6) !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
        }
        .mvs-input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            border-radius: 12px !important;
            font-size: 10px !important;
            font-weight: 700;
            text-transform: uppercase;
        }
        .mvs-input:focus { border-color: #c5a043 !important; outline: none; }
        .text-gold { color: #c5a043; }
        .bg-gold-gradient { background: linear-gradient(135deg, #c5a043 0%, #a38235 100%); }
        
        select option { background: #11141b; color: white; }
    </style>

    <div class="py-8 bg-[#0b0c10] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">
                        Staff <span class="text-gold">Financials</span>
                    </h2>
                    <p class="text-[9px] text-slate-500 font-bold tracking-[0.3em] uppercase mt-1">Liquidity Distribution Tracking</p>
                </div>
                <div class="flex gap-3">
                    {{-- Excel Export Button for Accountant --}}
                    <a href="{{ route('admin.export.excel', request()->all()) }}" class="text-[9px] font-black text-emerald-400 bg-emerald-500/5 border border-emerald-500/20 px-5 py-2 rounded-xl hover:bg-emerald-500 hover:text-black transition-all uppercase tracking-widest flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.ledger') }}" class="text-[9px] font-black text-slate-400 bg-white/5 border border-white/10 px-5 py-2 rounded-xl hover:bg-gold hover:text-black transition-all uppercase tracking-widest">
                        ← Back to Ledger
                    </a>
                </div>
            </div>

            {{-- Filters Section --}}
            <div class="mvs-card p-5 mb-8 border-gold-soft/10 shadow-xl">
                <form method="GET" action="{{ route('admin.staffBalances') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Select Staff Member</label>
                        <select name="user_id" class="mvs-input w-full px-4 py-3 cursor-pointer appearance-none">
                            <option value="">ALL STAFF MEMBERS</option>
                            @foreach($allStaff as $staffMember)
                                <option value="{{ $staffMember->id }}" {{ request('user_id') == $staffMember->id ? 'selected' : '' }}>
                                    {{ strtoupper($staffMember->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2 block">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="mvs-input w-full px-4 py-3 scheme-dark">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2 block">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="mvs-input w-full px-4 py-3 scheme-dark">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-gold-gradient text-black font-black text-[10px] uppercase h-[42px] px-6 rounded-xl flex-1 hover:opacity-90 transition shadow-lg shadow-gold/10">Apply Filters</button>
                        <a href="{{ route('admin.staffBalances') }}" class="bg-white/5 text-white font-black text-[10px] uppercase h-[42px] px-4 rounded-xl flex items-center justify-center hover:bg-white/10 transition">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Portfolio Summary Card --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="mvs-card p-6 border-l-4 border-l-gold">
                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mb-1">Total Outstanding Portfolio</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-gold font-black text-lg italic">SAR</span>
                        <h2 class="text-3xl font-black text-white tracking-tighter">
                            {{ number_format($staffData->sum(fn($s) => $s->calculated_balance), 2) }}
                        </h2>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="mvs-card overflow-hidden shadow-2xl">
                <div class="px-8 py-4 border-b border-white/5 bg-white/[0.01]">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em] flex items-center">
                        <span class="w-2 h-2 bg-gold rounded-full mr-3 shadow-[0_0_8px_#c5a043]"></span>
                        Staff Accountability Matrix
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-black/20">
                                <th class="px-8 py-5">Staff Member</th>
                                <th class="px-8 py-5 text-right">Total Assigned (+)</th>
                                <th class="px-8 py-5 text-right">Total Expenses (-)</th>
                                <th class="px-8 py-5 text-right">Net Balance / Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($staffData as $staff)
                            @php 
                                $bal = $staff->calculated_balance; 
                            @endphp
                            <tr class="group hover:bg-white/[0.02] transition">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-gold-gradient flex items-center justify-center text-black font-black text-sm shadow-md">
                                            {{ substr($staff->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-black text-slate-200 block text-[11px] uppercase group-hover:text-gold transition-colors">{{ $staff->name }}</span>
                                            <span class="text-[8px] uppercase text-slate-600 font-bold tracking-widest italic">{{ $staff->project_name ?? 'Operations Unit' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-[10px] font-black text-emerald-500/80 font-mono italic">{{ number_format($staff->total_received, 2) }}</span>
                                </td>
                                <td class="px-8 py-5 text-right text-rose-500 font-black text-[10px] font-mono">
                                    {{ number_format($staff->total_spent, 2) }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($bal > 0)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-white font-black text-xs font-mono">SAR {{ number_format($bal, 2) }}</span>
                                            <span class="text-[7px] font-black bg-amber-500/10 text-amber-500 border border-amber-500/20 px-2 py-0.5 rounded uppercase tracking-tighter">Pending Clearance</span>
                                        </div>
                                    @elseif($bal == 0)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-slate-500 font-black text-xs font-mono">0.00</span>
                                            <span class="text-[7px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 px-2 py-0.5 rounded uppercase tracking-tighter">All Cleared</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-rose-500 font-black text-xs font-mono">({{ number_format(abs($bal), 2) }})</span>
                                            <span class="text-[7px] font-black bg-rose-500/10 text-rose-500 border border-rose-500/20 px-2 py-0.5 rounded uppercase tracking-tighter">Overspent</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-slate-600 font-black text-[10px] uppercase tracking-[0.3em]">No personnel found for selected filters</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>