<x-app-layout>
    <div class="py-12 bg-[#0f1116] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header & Balance Cards --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-wider">
                        My Digital <span class="text-[#c5a043]">Wallet</span>
                    </h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1 italic">Personal Liquidity Overview</p>
                </div>
                
                {{-- Current Balance Badge --}}
                <div class="bg-[#1a1d24] border border-[#c5a043]/30 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-xl shadow-black/50">
                    <div class="bg-[#c5a043]/10 p-3 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#c5a043]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em]">Available Balance</p>
                        <h3 class="text-2xl font-black text-white italic">SAR {{ number_format(auth()->user()->cash_balance, 2) }}</h3>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-white/5 hover:bg-[#c5a043] hover:text-black text-white px-6 py-2.5 rounded-xl text-[10px] font-black border border-white/10 transition-all uppercase tracking-widest group">
                    <span class="mr-2 transform group-hover:-translate-x-1 transition-transform">←</span> Back
                </a>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-[#1a1d24] p-5 rounded-2xl border border-white/5">
                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mb-1">Total Received (+)</p>
                    <h4 class="text-xl font-black text-emerald-500 italic">SAR {{ number_format(auth()->user()->total_received, 2) }}</h4>
                </div>
                <div class="bg-[#1a1d24] p-5 rounded-2xl border border-white/5">
                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest mb-1">Total Spent (-)</p>
                    <h4 class="text-xl font-black text-rose-500 italic">SAR {{ number_format(auth()->user()->total_spent, 2) }}</h4>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-[#1a1d24] rounded-2xl shadow-2xl overflow-hidden border border-white/5">
                <div class="px-6 py-4 bg-black/20 border-b border-white/5">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-widest">Recent Wallet Activity</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-black/40 text-slate-500 border-b border-white/5">
                            <tr>
                                <th class="px-6 py-5 uppercase font-black tracking-widest">Date</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest">Type</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-right">Credit (+)</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-right">Debit (-)</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-right italic">Balance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            {{-- Fetching both Assignments and Approved Expenses --}}
                            @php
                                $walletLogs = \App\Models\Transaction::where('user_id', auth()->id())
                                    ->where('status', 'approved')
                                    ->latest()
                                    ->get();
                            @endphp

                            @forelse($walletLogs as $log)
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="text-slate-200 font-bold">{{ \Carbon\Carbon::parse($log->transaction_date)->format('d M, Y') }}</div>
                                        <div class="text-[9px] text-slate-600 font-medium uppercase mt-0.5">{{ $log->description }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($log->type == 'assignment')
                                            <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">Received Cash</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-widest border border-rose-500/20">Expense Paid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right font-bold {{ $log->type == 'assignment' ? 'text-emerald-400' : 'text-slate-700' }}">
                                        {{ $log->type == 'assignment' ? '+ ' . number_format($log->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right font-bold {{ $log->type == 'expense' ? 'text-rose-400' : 'text-slate-700' }}">
                                        {{ $log->type == 'expense' ? '- ' . number_format($log->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-[10px] font-black text-white bg-white/5 px-3 py-1 rounded-lg">
                                            SETTLED
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <p class="text-slate-600 font-black uppercase text-[10px] tracking-widest italic">Wallet is empty</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Info Footer --}}
            <div class="mt-8 flex items-center justify-between">
                <p class="text-slate-600 text-[9px] font-bold uppercase tracking-widest">
                    Authorized and Monitored by Modern Vision Solutions Admin
                </p>
                <div class="flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-[#c5a043] animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>