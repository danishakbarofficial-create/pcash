<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manager Approval Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-700">Pending Staff Requests</h3>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 uppercase text-xs font-bold text-gray-600">
                            <th class="p-3 border">Staff Name</th>
                            <th class="p-3 border">Description</th>
                            <th class="p-3 border">Amount</th>
                            <th class="p-3 border">Receipt</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="p-3 border font-semibold">{{ $req->user->name }}</td>
                                <td class="p-3 border">{{ $req->description }}</td>
                                <td class="p-3 border text-green-600 font-bold">SAR {{ number_format($req->amount, 2) }}</td>
                                <td class="p-3 border text-center">
                                    <a href="{{ asset('storage/' . $req->receipt_photo) }}" target="_blank" class="text-blue-500 underline">View Bill</a>
                                </td>
                                <td class="p-3 border flex space-x-2">
                                    {{-- Approve Button --}}
                                    <form action="{{ route('manager.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-green-600">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- Reject Button --}}
                                    <button onclick="openRejectModal({{ $req->id }})" class="bg-red-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-red-600">
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-5 text-center text-gray-500 italic">No pending requests from your staff.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script for Modal (Optional) --}}
    <script>
        function openRejectModal(id) {
            let reason = prompt("Enter rejection reason:");
            if (reason) {
                // Yahan aap rejection ki logic/route call kar sakte hain
                window.location.href = `/manager/reject/${id}?reason=` + encodeURIComponent(reason);
            }
        }
    </script>
</x-app-layout>