<x-app-layout>
    <div class="py-12 bg-[#0f1116] min-h-screen"> {{-- Premium Dark Theme --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Status Alerts --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-400 rounded-xl shadow-sm font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any() || session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border-l-4 border-red-500 text-red-400 rounded-xl shadow-sm text-xs font-bold">
                    <ul>
                        @if(session('error')) <li>⚠️ {{ session('error') }}</li> @endif
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT SIDE: EXPENSE FORM --}}
                <div class="lg:col-span-1">
                    <div class="bg-[#1a1d24] rounded-2xl shadow-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-bold text-[#c5a043] mb-6 flex items-center uppercase tracking-wider">
                            <span class="mr-2">🚀</span> Submit New Expense
                        </h3>
                        
                        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Category</label>
                                <select name="category_id" class="w-full bg-[#111318] border-white/10 rounded-lg text-sm text-slate-300 focus:border-[#c5a043] focus:ring-0" required>
                                    <option value="1">🍔 Food</option>
                                    <option value="2">⛽ Fuel</option>
                                    <option value="3">🔧 Material</option>
                                    <option value="4">🔧 Maintenance</option>
                                    <option value="5">📝 Office</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Expense Date</label>
                                <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="w-full bg-[#111318] border-white/10 rounded-lg text-sm text-slate-300 focus:border-[#c5a043] focus:ring-0" required>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Amount (SAR)</label>
                                <input type="number" step="0.01" name="amount" class="w-full bg-[#111318] border-white/10 rounded-lg text-sm text-[#c5a043] font-black focus:border-[#c5a043] focus:ring-0" placeholder="0.00" required>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Description</label>
                                <textarea name="description" class="w-full bg-[#111318] border-white/10 rounded-lg text-sm text-slate-300 focus:border-[#c5a043] focus:ring-0" rows="2" required></textarea>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Receipt Attachment</label>
                                <input type="file" name="receipt_photo" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#c5a043]/10 file:text-[#c5a043]" required>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-[#c5a043] to-[#a38435] text-black py-3 rounded-xl font-black text-[11px] uppercase transition-all hover:opacity-90">
                                Submit My Request
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT SIDE: TABLES --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. PENDING STAFF REQUESTS (Hierarchy Filter) --}}
                    <div class="bg-[#1a1d24] rounded-2xl shadow-2xl overflow-hidden border border-white/5">
                        <div class="p-4 bg-white/[0.02] border-b border-white/5 flex justify-between items-center">
                            <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest">Awaiting My Approval (Staff)</h3>
                            <span class="bg-[#c5a043]/10 text-[#c5a043] text-[9px] px-2 py-1 rounded font-bold">MANAGER QUEUE</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-white/[0.01] text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4 uppercase font-black">Staff / Details</th>
                                        <th class="px-6 py-4 uppercase font-black text-right">Amount</th>
                                        <th class="px-6 py-4 uppercase font-black text-center">Receipt</th>
                                        <th class="px-6 py-4 uppercase font-black text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    {{-- Filtered for pending_manager status --}}
                                    @forelse(\App\Models\Transaction::where('status', 'pending_manager')->where('user_id', '!=', auth()->id())->get() as $expense)
                                        <tr class="hover:bg-white/[0.02]">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-200">{{ $expense->user->name ?? 'Unknown' }}</div>
                                                <div class="text-[10px] text-slate-500">{{ $expense->description }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right font-black text-amber-500">SAR {{ number_format($expense->amount, 2) }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($expense->receipt_path)
                                                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-[#c5a043] font-black uppercase text-[10px] border-b border-[#c5a043]/20">View</a>
                                                @else
                                                    <span class="text-slate-700 italic">No File</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <form action="{{ route('manager.approve', $expense->id) }}" method="POST">
                                                        @csrf
                                                        <button class="bg-[#c5a043] text-black px-4 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-white transition-all">Approve</button>
                                                    </form>
                                                    <form action="{{ route('admin.reject', $expense->id) }}" method="POST">
                                                        @csrf
                                                        <button class="border border-rose-500/30 text-rose-500 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-rose-500 hover:text-white transition-all">Reject</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-12 text-center text-slate-600 text-[10px] uppercase font-bold tracking-widest italic">No pending staff requests for review</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. MY EXPENSE HISTORY --}}
                    <div class="bg-[#1a1d24] rounded-2xl shadow-2xl overflow-hidden border border-white/5">
                        <div class="p-4 bg-[#c5a043]/5 border-b border-[#c5a043]/10">
                            <h3 class="font-black text-[#c5a043] text-[10px] uppercase tracking-widest">My Personal Expense History</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-white/[0.01] text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4 uppercase font-black">Date</th>
                                        <th class="px-6 py-4 uppercase font-black">Description</th>
                                        <th class="px-6 py-4 uppercase font-black">Amount</th>
                                        <th class="px-6 py-4 uppercase font-black text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse(\App\Models\Transaction::where('user_id', auth()->id())->where('type', 'expense')->latest()->take(10)->get() as $myExp)
                                        <tr class="hover:bg-white/[0.01]">
                                            <td class="px-6 py-4 text-slate-500 font-bold">{{ $myExp->transaction_date }}</td>
                                            <td class="px-6 py-4 font-bold text-slate-300">{{ $myExp->description }}</td>
                                            <td class="px-6 py-4 font-black text-[#c5a043]">SAR {{ number_format($myExp->amount, 2) }}</td>
                                            <td class="px-6 py-4 text-right">
                                                @php
                                                    $statusClasses = [
                                                        'approved' => 'bg-emerald-500/10 text-emerald-500',
                                                        'pending_manager' => 'bg-amber-500/10 text-amber-500',
                                                        'pending_admin' => 'bg-blue-500/10 text-blue-400',
                                                        'rejected' => 'bg-rose-500/10 text-rose-500',
                                                    ];
                                                    $class = $statusClasses[$myExp->status] ?? 'bg-slate-500/10 text-slate-400';
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $class }}">
                                                    {{ str_replace('_', ' ', $myExp->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-12 text-center text-slate-600">No records found.</td></tr>
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