<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #0b0c10; 
            color: #e2e8f0;
        }
        .mvs-card { 
            background: #151921; 
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
        }
        .mvs-gold { color: #c5a043; }
        
        .filter-input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11px;
            outline: none;
            transition: all 0.3s;
        }
        .filter-input:focus {
            border-color: #c5a043;
            box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.1);
        }

        select.filter-input option {
            background: #151921;
            color: white;
        }

        @media print {
            .no-print { display: none; }
            body { background: white; color: black; }
            .mvs-card { border: 1px solid #ccc !important; background: white !important; color: black !important; }
            .text-white, .mvs-gold, .text-emerald-500, .text-rose-500 { color: black !important; }
        }

        .excel-text { display: none; }
        @media print {
            .excel-text { display: inline; }
            .screen-icon { display: none; }
        }
    </style>

    <div class="min-h-screen pb-12">
        {{-- Header --}}
        <div class="bg-[#151921] border-b border-white/5 py-6 mb-8 no-print">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-black tracking-tight text-white italic uppercase">Master <span class="mvs-gold">Audit Ledger</span></h2>
                    <p class="text-slate-500 text-[10px] font-bold tracking-widest uppercase mt-1">Full Transaction History — Riyadh Unit</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="exportToExcel()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2 rounded-lg text-[10px] font-bold transition-all uppercase flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Excel
                    </button>
                    <button onclick="window.print()" class="bg-white/5 hover:bg-white/10 text-white px-5 py-2 rounded-lg text-[10px] font-bold border border-white/10 transition-all uppercase">
                        Print / Download PDF
                    </button>
                    <a href="{{ route('dashboard') }}" class="bg-white text-black px-5 py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-gray-200 transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter Form --}}
            <div class="mvs-card p-6 mb-8 no-print">
                <form action="{{ route('admin.ledger') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full filter-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full filter-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Staff Member</label>
                        <select name="user_id" class="w-full filter-input">
                            <option value="">All Transactions</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-[#c5a043] text-black font-black text-[10px] uppercase py-2.5 rounded-lg hover:bg-yellow-600 transition-all">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.ledger') }}" class="px-3 py-2.5 bg-white/5 border border-white/10 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-white/10 flex items-center justify-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Summary Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="mvs-card p-6 border-t-4 border-emerald-500">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Total Deposits (Inflow)</p>
                    <h3 class="text-3xl font-black text-emerald-500 italic">SAR {{ number_format($totalInflow, 2) }}</h3>
                </div>
                
                <div class="mvs-card p-6 border-t-4 border-rose-500">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Total Vault Outflow</p>
                    <h3 class="text-3xl font-black text-rose-500 italic">SAR {{ number_format($totalOutflow, 2) }}</h3>
                </div>

                <div class="mvs-card p-6 border-t-4 border-[#c5a043] bg-gradient-to-br from-[#151921] to-[#1a1f29]">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Net Vault Balance</p>
                    <h3 class="text-3xl font-black mvs-gold italic">SAR {{ number_format($vaultBalance, 2) }}</h3>
                </div>
            </div>

            {{-- Table --}}
            <div class="mvs-card overflow-hidden shadow-2xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                    <h3 class="font-bold text-xs uppercase tracking-[0.2em] text-white italic">Complete Transaction Log</h3>
                    <span class="text-[9px] font-bold text-slate-500 uppercase">Showing All Approved Movements</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table id="ledgerTable" class="w-full text-left">
                        <thead class="bg-black/30 text-[9px] uppercase text-slate-500 font-bold tracking-widest border-b border-white/5">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Activity Type</th>
                                <th class="px-6 py-4">Staff / Account</th>
                                <th class="px-6 py-4">Category</th> 
                                <th class="px-6 py-4">Description</th> 
                                <th class="px-6 py-4 text-center">Receipt</th> 
                                <th class="px-6 py-4 text-right">Debit (-)</th>
                                <th class="px-6 py-4 text-right">Credit (+)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($allTransactions as $t)
                                @php
                                    // Logic to extract category from [Brackets]
                                    $pattern = '/\[(.*?)\]/';
                                    preg_match($pattern, $t->description, $matches);
                                    $extractedCategory = isset($matches[1]) ? $matches[1] : ($t->category ?? 'General');
                                    // Remove brackets from description for display
                                    $cleanDescription = preg_replace('/\[.*?\]\s*/', '', $t->description);
                                @endphp
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-400">{{ $t->transaction_date }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase border {{ $t->type == 'assignment' ? 'border-emerald-500/20 text-emerald-500 bg-emerald-500/5' : 'border-rose-500/20 text-rose-500 bg-rose-500/5' }}">
                                            {{ $t->type == 'assignment' ? 'Vault Assignment' : 'Staff Expense' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-black text-white italic uppercase">{{ $t->user->name }}</td>
                                    
                                    {{-- Category Column --}}
                                    <td class="px-6 py-4 text-[10px] font-bold text-[#c5a043] uppercase">
                                        {{ $extractedCategory }}
                                    </td>

                                    {{-- Description Column --}}
                                    <td class="px-6 py-4 text-[10px] text-slate-500 italic">
                                        {{ $cleanDescription }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        @if($t->type == 'expense' && $t->receipt_path)
                                            <a href="{{ asset('storage/' . $t->receipt_path) }}" target="_blank" class="screen-icon text-[#c5a043] hover:text-white transition-colors inline-block">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </a>
                                            <span class="excel-text hidden text-[10px] text-slate-500 font-bold">✔ Attachment</span>
                                        @else
                                            <span class="text-slate-800 text-[8px] font-bold uppercase">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right text-[11px] font-black {{ $t->type == 'expense' ? 'text-rose-500' : 'text-slate-700' }}">
                                        {{ $t->type == 'expense' ? number_format($t->amount, 2) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-[11px] font-black {{ $t->type == 'assignment' ? 'text-emerald-500' : 'text-slate-700' }}">
                                        {{ $t->type == 'assignment' ? number_format($t->amount, 2) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($allTransactions->hasPages())
                <div class="px-6 py-4 border-t border-white/5 bg-black/20 no-print">
                    {{ $allTransactions->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Export Logic --}}
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        function exportToExcel() {
            const icons = document.querySelectorAll('.screen-icon');
            const texts = document.querySelectorAll('.excel-text');
            
            icons.forEach(i => i.style.display = 'none');
            texts.forEach(t => t.style.display = 'inline');

            let table = document.getElementById("ledgerTable");
            TableToExcel.convert(table, {
                name: "MVS_Ledger_Report_" + new Date().toISOString().slice(0,10) + ".xlsx",
                sheet: { name: "Transactions" }
            }).then(() => {
                icons.forEach(i => i.style.display = 'inline-block');
                texts.forEach(t => t.style.display = 'none');
            });
        }
    </script>
</x-app-layout>