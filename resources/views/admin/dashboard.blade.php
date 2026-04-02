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
        .btn-primary {
            background: #c5a043;
            color: #000;
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #d4b465;
            transform: translateY(-1px);
        }
        .stat-label {
            color: #94a3b8;
            font-size: 0.70rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }
    </style>

    <div class="min-h-screen pb-12">
        {{-- Header & Navigation --}}
        <div class="bg-[#151921] border-b border-white/5 py-5 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    {{-- Logo & Title --}}
                    <div class="w-full md:w-auto flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold tracking-tight text-white italic uppercase">Admin <span class="mvs-gold">Panel</span></h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                <p class="text-slate-500 text-[9px] font-bold tracking-widest uppercase">Riyadh Central Vault (SAR)</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Actions Menu --}}
                    <div class="w-full md:w-auto grid grid-cols-3 md:flex md:flex-row gap-2">
                        <a href="{{ route('admin.addCash') }}" class="btn-primary px-4 py-2.5 rounded-lg text-[10px] uppercase block text-center">+ Vault</a>
                        <a href="{{ route('admin.assignList') }}" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2.5 rounded-lg text-[10px] font-bold border border-white/10 uppercase block text-center">Assign</a>
                        <a href="{{ route('admin.projects.index') }}" class="bg-[#c5a043]/10 hover:bg-[#c5a043]/20 text-[#c5a043] px-4 py-2.5 rounded-lg text-[10px] font-black border border-[#c5a043]/20 uppercase italic block text-center">📁 Projects</a>
                        <a href="{{ route('admin.ledger') }}" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2.5 rounded-lg text-[10px] font-bold border border-white/10 uppercase block text-center">Ledger</a>
                        <a href="{{ route('admin.users.index') }}" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2.5 rounded-lg text-[10px] font-bold border border-white/10 uppercase block text-center">Staff</a>
                        <a href="{{ route('admin.staffBalances') }}" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2.5 rounded-lg text-[10px] font-bold border border-white/10 uppercase block text-center">Balances</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alerts --}}
            @if(session('error'))
                <div class="mb-6">
                    <div class="bg-rose-500/10 border border-rose-500/20 text-rose-500 px-4 py-3 rounded-xl">
                        <span class="text-[10px] font-black uppercase">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Master Vault Card --}}
            <div class="mvs-card p-8 mb-8 relative overflow-hidden bg-gradient-to-br from-[#1a1f29] to-[#151921]">
                <div class="relative z-10">
                    <p class="stat-label mb-3">Master Vault Liquidity</p>
                    <div class="flex items-baseline gap-3">
                        <span class="text-slate-500 text-xl font-black italic">SAR</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter text-white italic">
                            @php $vault = \App\Models\Vault::first(); @endphp
                            {{ number_format($vault->total_balance ?? 0, 2) }}
                        </h1>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @php
                    $stats = [
                        ['label' => 'Awaiting Mgr', 'val' => $expenses->where('status', 'pending_manager')->count(), 'color' => 'text-amber-500'],
                        ['label' => 'Pending Admin', 'val' => $expenses->where('status', 'pending_admin')->count(), 'color' => 'mvs-gold'],
                        ['label' => 'Total Outflow', 'val' => number_format($expenses->where('status', 'approved')->where('type', 'assignment')->sum('amount'), 0), 'color' => 'text-rose-500'],
                        ['label' => 'Rejections', 'val' => $expenses->where('status', 'rejected')->count(), 'color' => 'text-slate-400'],
                    ];
                @endphp
                @foreach($stats as $stat)
                <div class="mvs-card p-4 bg-white/[0.01]">
                    <p class="stat-label mb-1">{{ $stat['label'] }}</p>
                    <h3 class="text-lg font-extrabold {{ $stat['color'] }}">{{ $stat['val'] }}</h3>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Queue Table --}}
                <div class="lg:col-span-2">
                    <div class="mvs-card overflow-hidden">
                        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                            <h3 class="font-bold text-[11px] uppercase tracking-[0.2em] text-white italic">Final Settlement Queue</h3>
                            <span class="bg-blue-500/10 text-blue-400 text-[9px] px-3 py-1 rounded-full font-black">
                                {{ $expenses->where('status', 'pending_admin')->count() }} TO PROCESS
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[600px]">
                                <thead class="bg-black/30 text-[9px] uppercase text-slate-500 font-bold border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4">Date & Details</th>
                                        <th class="px-6 py-4">Staff Details</th>
                                        <th class="px-6 py-4">Amount</th>
                                        <th class="px-6 py-4 text-center">Receipt</th>
                                        <th class="px-6 py-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($expenses->where('status', 'pending_admin') as $e)
                                        <tr class="hover:bg-white/[0.03] transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="text-[11px] font-black text-white italic">{{ $e->transaction_date }}</div>
                                                <div class="text-[9px] font-bold text-[#c5a043] uppercase tracking-tighter">[{{ $e->category ?? 'N/A' }}] {{ Str::limit($e->description, 20) }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-[12px] font-bold text-white">{{ $e->user->name ?? 'User' }}</div>
                                                <p class="text-[8px] font-black uppercase {{ $e->user->cash_balance < $e->amount ? 'text-rose-500 animate-pulse' : 'text-emerald-500/50' }}">
                                                    Wallet: SAR {{ number_format($e->user->cash_balance, 2) }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-[13px] font-black text-white italic">SAR {{ number_format($e->amount, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($e->receipt_path)
                                                    <a href="{{ asset('storage/' . $e->receipt_path) }}" target="_blank" class="text-[#c5a043] font-black border-b border-[#c5a043]/30 text-[9px] uppercase">View Receipt</a>
                                                @else
                                                    <span class="text-slate-700 text-[9px] font-bold uppercase italic">No Receipt</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('admin.expense.finalize', $e->id) }}" method="POST">
                                                        @csrf
                                                        <button class="bg-white text-black px-4 py-1.5 rounded-lg text-[9px] font-black uppercase hover:bg-emerald-500 hover:text-white transition-all {{ $e->user->cash_balance < $e->amount ? 'opacity-30' : '' }}">Settle</button>
                                                    </form>
                                                    <form action="{{ route('admin.reject', $e->id) }}" method="POST">
                                                        @csrf
                                                        <button class="text-rose-500 border border-rose-500/20 px-4 py-1.5 rounded-lg text-[9px] font-black uppercase hover:bg-rose-500/10">Reject</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-700 text-[10px] font-bold uppercase italic tracking-widest">No pending settlements in queue</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Financial Audit Log --}}
                <div class="space-y-4">
                    <h3 class="px-2 font-bold text-[10px] uppercase tracking-[0.3em] text-slate-500 italic">Financial Audit Log</h3>
                    <div class="mvs-card p-5 space-y-5">
                        @foreach($expenses->whereIn('status', ['approved', 'rejected'])->sortByDesc('updated_at')->take(5) as $history)
                        <div class="flex items-start justify-between pb-4 border-b border-white/5 last:border-0 last:pb-0">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $history->status == 'approved' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                    <p class="text-[11px] font-bold text-white">{{ $history->user->name ?? 'User' }}</p>
                                </div>
                                <p class="text-[9px] text-slate-400 font-black uppercase">{{ $history->type == 'assignment' ? 'Cash Assigned' : 'Expense Settled' }}</p>
                                <span class="text-[8px] text-slate-600 uppercase">{{ $history->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[11px] font-black italic {{ $history->status == 'approved' ? 'text-white' : 'text-slate-600 line-through' }}">
                                    SAR {{ number_format($history->amount, 0) }}
                                </p>
                                <span class="text-[8px] font-black uppercase {{ $history->status == 'approved' ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $history->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>