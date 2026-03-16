<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash | New Entry (SAR)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-indigo-700 p-4 text-white shadow-lg mb-10">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-tight">PettyCash <span class="text-indigo-200">Riyadh</span></h1>
            <div class="space-x-4">
                <a href="{{ route('dashboard') }}" class="hover:underline text-sm font-medium">Dashboard</a>
                <a href="#" class="bg-indigo-800 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-900 transition">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-xl overflow-hidden p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-wide">New Transaction</h2>
            <p class="text-xs text-gray-400 font-bold mt-1">RIYADH OFFICE LOG</p>
        </div>
        
        <form action="/save-transaction" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Description</label>
                <input type="text" name="description" required placeholder="e.g. Fuel for Delivery, Office Supplies" 
                       class="mt-1 block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Amount (SAR)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400 font-bold text-sm">SAR</span>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                               class="pl-12 mt-1 block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
                    <select name="category" class="mt-1 block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="fuel">Fuel / Petrol</option>
                        <option value="supplies">Office Supplies</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="mandoob">Mandoob / Govt Fees</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Date</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                       class="mt-1 block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-100 uppercase tracking-widest">
                Save Transaction
            </button>
        </form>
    </div>
</body>
</html>