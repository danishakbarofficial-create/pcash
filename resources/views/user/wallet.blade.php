<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-indigo-600 uppercase tracking-widest flex items-center transition-all hover:translate-x-[-4px]">
                    ⬅️ Back to Dashboard
                </a>
            </div>

            <div class="bg-gradient-to-br from-green-600 to-teal-700 p-8 rounded-3xl shadow-2xl text-white mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-medium opacity-80 uppercase tracking-widest">Current Balance (Riyadh Office)</p>
                    <h3 class="text-4xl font-black mt-2">SAR {{ number_format(auth()->user()->cash_balance, 2) }}</h3>
                </div>
                <div class="absolute -right-5 -bottom-5 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-5 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Cash Assignment History</h3>
                    <span class="text-[9px] bg-green-100 text-green-700 px-2 py-1 rounded-md font-bold uppercase tracking-wider">Income Logs (SAR)</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400">
                                <th class="px-6 py-3 border-b">Date</th>
                                <th class="px-6 py-3 border-b">Amount Received</th>
                                <th class="px-6 py-3 border-b text-center">Receipt / Proof</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($receivedCash as $log)
                                <tr class="hover:bg-gray-50/50 transition-all">
                                    <td class="px-6 py-4 text-xs text-gray-500 font-medium">
                                        {{ $log->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-green-600">
                                        + SAR {{ number_format($log->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($log->proof)
                                            <a href="{{ asset('storage/' . $log->proof) }}" target="_blank" 
                                               class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-bold hover:bg-indigo-100 transition shadow-sm">
                                                🖼️ View Proof
                                            </a>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic font-medium">No proof attached</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-xs italic font-medium">
                                        No cash assignments found in your history.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>