<x-app-layout>
    <div class="py-12 bg-[#0b0c10] min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-6">
                <h2 class="text-[#c5a043] text-2xl font-black uppercase tracking-widest border-l-4 border-[#c5a043] pl-4">
                    Real-Time Analytics
                </h2>

                <form action="{{ route('admin.reporting') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-[#151921] p-4 rounded-xl border border-white/5 shadow-xl">
                    <div>
                        <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Select Project</label>
                        <select name="project" class="bg-[#0b0c10] border-white/10 text-white text-xs rounded-lg focus:ring-[#c5a043] focus:border-[#c5a043] min-w-[180px]">
                            <option value="">All Active Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1">From</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="bg-[#0b0c10] border-white/10 text-white text-xs rounded-lg focus:ring-[#c5a043]">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1">To</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="bg-[#0b0c10] border-white/10 text-white text-xs rounded-lg focus:ring-[#c5a043]">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-[#c5a043] hover:bg-[#a68636] text-black font-extrabold py-2 px-6 rounded-lg text-xs transition-all uppercase">
                            Apply Filter
                        </button>
                        <a href="{{ route('admin.reporting') }}" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 py-2 px-4 rounded-lg text-xs transition-all flex items-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-[#151921] p-4 rounded-xl border border-white/5">
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Total Filtered Expense</p>
                    <p class="text-xl font-black text-[#c5a043]">SAR {{ number_format($categoryData->sum(), 2) }}</p>
                </div>
                <div class="bg-[#151921] p-4 rounded-xl border border-white/5 text-center">
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Active Staff</p>
                    <p class="text-xl font-black text-white">{{ $staffCash->count() }}</p>
                </div>
                 <div class="bg-[#151921] p-4 rounded-xl border border-white/5 text-right">
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Total Projects</p>
                    <p class="text-xl font-black text-white">{{ count($projects) }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-[#151921] p-6 rounded-2xl border border-white/10 shadow-2xl flex flex-col items-center">
                    <div class="w-full flex justify-between items-center mb-6">
                        <h3 class="text-white text-sm font-bold uppercase tracking-tighter">Category-wise Spending</h3>
                        <span class="text-[10px] bg-[#c5a043]/10 text-[#c5a043] px-2 py-1 rounded tracking-widest uppercase">Live Data</span>
                    </div>
                    <div class="relative w-full" style="height: 280px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <div class="bg-[#151921] p-6 rounded-2xl border border-white/10 shadow-2xl flex flex-col items-center">
                    <h3 class="text-white text-sm font-bold uppercase mb-6 self-start tracking-tighter">Petty Cash per Staff</h3>
                    <div class="relative w-full" style="height: 280px;">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Category-wise Donut Chart
        const catLabels = {!! json_encode($categoryData->keys()) !!};
        const catTotals = {!! json_encode($categoryData->values()) !!};

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catTotals,
                    backgroundColor: ['#c5a043', '#ffffff', '#4e4e4e', '#8a6d2b', '#313131', '#d4af37'],
                    borderColor: '#151921',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 11 }, padding: 20, usePointStyle: true } }
                }
            }
        });

        // Staff Bar Chart
        new Chart(document.getElementById('staffChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($staffCash->pluck('name')) !!},
                datasets: [{
                    label: 'SAR',
                    data: {!! json_encode($staffCash->pluck('total_received')) !!},
                    backgroundColor: '#c5a043',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#6b7280' } },
                    x: { grid: { display: false }, ticks: { color: '#ffffff' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>