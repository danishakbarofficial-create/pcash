<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 bg-[#0f1116] min-h-screen"> {{-- Dark Theme Background --}}
        <div class="max-w-4xl mx-auto">
            
            {{-- Header & Navigation --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-wider">
                        Staff <span class="text-[#c5a043]">Portal</span>
                    </h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">Riyadh Operations / Petty Cash</p>
                </div>
                <div class="flex items-center">
                    {{-- History Page Link --}}
                    <a href="{{ route('staff.history') }}" class="group flex items-center bg-[#c5a043]/5 hover:bg-[#c5a043] border border-[#c5a043]/20 px-5 py-2.5 rounded-xl transition-all">
                        <span class="mr-2 text-lg">⏳</span>
                        <span class="text-[10px] font-black text-[#c5a043] group-hover:text-black uppercase tracking-widest">My History</span>
                    </a>
                </div>
            </div>

            {{-- Alerts Section --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/30 border-l-4 border-red-500 text-red-200 text-sm rounded-xl shadow-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center">⚠️ <span class="ml-2 font-bold">{{ $error }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-200 text-sm rounded-xl shadow-sm flex items-center font-bold">
                    <span class="mr-2 text-lg">✅</span> {{ session('success') }}
                </div>
            @endif

            {{-- New Expense Form --}}
            <div class="bg-[#1a1d24] rounded-2xl shadow-2xl p-8 border border-white/5 mb-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-extrabold text-white flex items-center tracking-tight">
                        <span class="mr-3 bg-[#c5a043]/10 p-2 rounded-lg text-xl text-[#c5a043]">📝</span> 
                        Submit New Expense
                    </h3>
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">Transaction Entry</span>
                </div>
                
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Category Field --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Expense Category</label>
                            <select name="category_id" class="w-full bg-[#111318] border-white/10 rounded-xl p-3 text-sm text-gray-200 focus:ring-2 focus:ring-[#c5a043] focus:border-[#c5a043] outline-none transition-all" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="1">🍔 Food</option>
                                <option value="2">⛽ Fuel / Petrol</option>
                                <option value="3">🏗️ Material Purchase</option>
                                <option value="4">🔧 Maintenance</option>
                                <option value="5">📁 Office Expenses</option>
                                <option value="6">📦 Others</option>
                            </select>
                        </div>

                        {{-- Date Field --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Date of Expense</label>
                            <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" 
                                class="w-full bg-[#111318] border-white/10 rounded-xl p-3 text-sm text-gray-200 focus:ring-2 focus:ring-[#c5a043] outline-none" required>
                        </div>

                        {{-- Description Field --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reason / Description</label>
                            <input type="text" name="description" placeholder="e.g. Fuel for generator or Office Stationary" 
                                class="w-full bg-[#111318] border-white/10 rounded-xl p-3 text-sm text-gray-200 focus:ring-2 focus:ring-[#c5a043] outline-none" required>
                        </div>

                        {{-- Amount Field --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Amount (SAR)</label>
                            <input type="number" step="0.01" name="amount" placeholder="0.00" 
                                class="w-full bg-[#111318] border-white/10 rounded-xl p-3 text-sm font-black text-[#c5a043] focus:ring-2 focus:ring-[#c5a043] outline-none placeholder-[#c5a043]/30" required>
                        </div>

                        {{-- File Upload --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Attach Receipt (Image)</label>
                            <input type="file" name="receipt_photo" accept="image/*" required
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-[#c5a043]/10 file:text-[#c5a043] hover:file:bg-[#c5a043] hover:file:text-black transition-all cursor-pointer">
                        </div>

                        {{-- Submit Button --}}
                        <div class="md:col-span-2 mt-4">
                            <button type="submit" 
                                class="w-full bg-gradient-to-r from-[#c5a043] to-[#a38435] hover:opacity-90 text-black py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-[#c5a043]/10 transition-all transform active:scale-[0.98] flex items-center justify-center">
                                <span class="mr-2 text-lg">🚀</span> Submit Expense Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Info Note --}}
            <div class="bg-[#c5a043]/5 border border-[#c5a043]/10 p-4 rounded-xl text-center">
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest italic">
                    All submissions are reviewed by the Manager before Admin final settlement.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>