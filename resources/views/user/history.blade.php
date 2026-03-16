<x-app-layout>
    <div class="py-12 bg-[#0f1116] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-wider">
                        My Expense <span class="text-[#c5a043]">History</span>
                    </h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1 italic">Riyadh Petty Cash Management</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center bg-white/5 hover:bg-[#c5a043] hover:text-black text-white px-6 py-2.5 rounded-xl text-[10px] font-black border border-white/10 transition-all uppercase tracking-widest group">
                    <span class="mr-2 transform group-hover:-translate-x-1 transition-transform">←</span> Back to Dashboard
                </a>
            </div>

            {{-- History Table --}}
            <div class="bg-[#1a1d24] rounded-2xl shadow-2xl overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-black/40 text-slate-500 border-b border-white/5">
                            <tr>
                                <th class="px-6 py-5 uppercase font-black tracking-widest">Date</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest">Reason / Description</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-right">Amount</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-center">Receipt</th>
                                <th class="px-6 py-5 uppercase font-black tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse(\App\Models\Transaction::where('user_id', auth()->id())->where('type', 'expense')->latest()->get() as $log)
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="text-slate-200 font-bold">{{ \Carbon\Carbon::parse($log->transaction_date)->format('d M, Y') }}</div>
                                        <div class="text-[9px] text-slate-600 font-medium uppercase mt-0.5 tracking-tighter">{{ $log->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-300 group-hover:text-[#c5a043] transition-colors">{{ $log->description }}</div>
                                        <div class="text-[9px] text-slate-500 font-black uppercase mt-1 inline-block bg-white/5 px-2 py-0.5 rounded">
                                            {{ $log->category->name ?? 'General' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-white italic text-sm">
                                        SAR {{ number_format($log->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($log->receipt_path)
                                            <a href="{{ asset('storage/' . $log->receipt_path) }}" target="_blank" 
                                               class="px-3 py-1.5 bg-[#c5a043]/10 text-[#c5a043] rounded-lg border border-[#c5a043]/20 text-[10px] font-black hover:bg-[#c5a043] hover:text-black transition-all uppercase tracking-tighter">
                                                View Receipt
                                            </a>
                                        @else
                                            <span class="text-slate-700 text-[10px] font-black uppercase italic">No File</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        @php
                                            $statusData = match($log->status) {
                                                'approved' => ['label' => 'SETTLED', 'class' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20'],
                                                'pending_manager' => ['label' => 'WITH MANAGER', 'class' => 'bg-amber-500/10 text-amber-500 border-amber-500/20'],
                                                'pending_admin' => ['label' => 'WITH ADMIN', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                                                'rejected' => ['label' => 'REJECTED', 'class' => 'bg-rose-500/10 text-rose-500 border-rose-500/20'],
                                                default => ['label' => strtoupper($log->status), 'class' => 'bg-slate-500/10 text-slate-400 border-white/5'],
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg border text-[9px] font-black tracking-tighter {{ $statusData['class'] }}">
                                            {{ $statusData['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="bg-white/5 inline-block p-4 rounded-full mb-3 text-slate-700">📜</div>
                                        <p class="text-slate-600 font-black uppercase text-[10px] tracking-widest">No transaction records found in your account</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Note --}}
            <div class="mt-8 p-4 border border-white/5 rounded-2xl bg-black/20 flex items-center gap-4">
                <div class="bg-[#c5a043]/10 p-2 rounded-lg text-lg">💡</div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest leading-relaxed">
                    Note: Only <span class="text-emerald-500">Settled</span> transactions have been deducted from your cash balance. 
                    Pending requests are still under review.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>