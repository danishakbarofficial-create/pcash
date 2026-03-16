<x-app-layout>
    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c5a043; border-radius: 10px; }

        /* Dark Input Styles */
        .mvs-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 10px !important;
            font-size: 0.8rem !important;
            padding: 0.75rem 1rem !important;
        }
        .mvs-input:focus {
            border-color: #c5a043 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.2) !important;
        }

        /* Fixed Label Visibility */
        .stat-label-white {
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            display: block;
            margin-bottom: 6px;
        }
        
        /* Table Header Specific Fix */
        .mvs-table-head {
            color: #c5a043 !important;
            font-size: 9px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            padding: 1rem 1.5rem !important;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        select option { background: #151921; color: white; }
    </style>

    <div class="min-h-screen pb-12 bg-black">
        <div class="bg-[#151921] border-b border-white/5 py-6 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-black text-white italic uppercase tracking-tighter">
                    Enterprise <span class="text-[#c5a043]">User Management</span>
                </h2>
                <p class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-1">Control staff access, hierarchies and permissions</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Left: Registration Form --}}
                <div class="lg:col-span-4">
                    <div class="bg-[#151921] rounded-3xl p-6 border border-white/5 shadow-2xl sticky top-8">
                        <h3 class="text-white font-black text-xs uppercase tracking-widest mb-8 border-b border-white/5 pb-4">
                            Register New Staff
                        </h3>
                        
                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="stat-label-white">Full Name</label>
                                <input type="text" name="name" required class="mvs-input w-full" placeholder="Full Name">
                            </div>

                            <div>
                                <label class="stat-label-white">Email Address</label>
                                <input type="email" name="email" required class="mvs-input w-full" placeholder="email@mvskasa.com">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="stat-label-white">Password</label>
                                    <input type="password" name="password" required class="mvs-input w-full">
                                </div>
                                <div>
                                    <label class="stat-label-white">Confirm</label>
                                    <input type="password" name="password_confirmation" required class="mvs-input w-full">
                                </div>
                            </div>

                            <div>
                                <label class="stat-label-white">Account Role</label>
                                <select name="role" id="roleSelect" onchange="updateReportingList()" class="mvs-input w-full font-bold">
                                    <option value="staff">STAFF (Submitter)</option>
                                    <option value="manager">MANAGER (Head)</option>
                                    <option value="admin">ADMIN (Final Approval)</option>
                                </select>
                            </div>

                            <div id="reportingDiv">
                                <label class="stat-label-white" id="reportingLabel">Reporting To</label>
                                <select name="reporting_to" id="reportingSelect" class="mvs-input w-full border-[#c5a043]/20 bg-[#c5a043]/5 text-[#c5a043] font-bold">
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="stat-label-white">Project Name</label>
                                    <input type="text" name="project_name" placeholder="Riyadh Metro" class="mvs-input w-full">
                                </div>
                                <div>
                                    <label class="stat-label-white">Cost Center</label>
                                    <input type="text" name="cost_center" placeholder="CC-901" class="mvs-input w-full">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#c5a043] hover:bg-[#d4b35a] text-black font-black py-4 rounded-2xl text-[11px] uppercase tracking-[0.15em] mt-6 shadow-xl transition-all active:scale-95">
                                Create User Account
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: User List Table --}}
                <div class="lg:col-span-8">
                    <div class="bg-[#151921] rounded-3xl border border-white/5 overflow-hidden shadow-2xl">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-black/40">
                                    <th class="mvs-table-head text-left">Name & Email</th>
                                    <th class="mvs-table-head text-left">Role & Hierarchy</th>
                                    <th class="mvs-table-head text-right">Cash Balance</th>
                                    <th class="mvs-table-head text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($users as $user)
                                <tr class="hover:bg-white/[0.02] transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="text-[13px] font-bold text-white tracking-tight">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-medium">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter 
                                            {{ $user->role == 'admin' ? 'bg-[#c5a043]/10 text-[#c5a043]' : ($user->role == 'manager' ? 'bg-blue-500/10 text-blue-400' : 'bg-slate-500/10 text-slate-400') }}">
                                            {{ $user->role }}
                                        </span>
                                        
                                        @if($user->reporting_to)
                                            <div class="text-[9px] text-slate-500 mt-1">
                                                Boss: <span class="text-white opacity-80 font-bold uppercase">{{ \App\Models\User::find($user->reporting_to)->name ?? 'N/A' }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-1 font-bold text-[#c5a043] uppercase text-[8px] opacity-80">
                                            {{ $user->project_name ?? 'GENERAL' }} <span class="text-slate-700 mx-1">|</span> {{ $user->cost_center ?? 'NO-CC' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right font-black italic">
                                        <span class="text-[8px] text-slate-500 mr-1 not-italic font-bold">SAR</span>
                                        <span class="text-sm {{ $user->cash_balance > 0 ? 'text-emerald-500' : 'text-slate-600' }}">
                                            {{ number_format($user->cash_balance, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-center gap-4">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-slate-500 hover:text-[#c5a043] transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Permanent delete user?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-700 hover:text-rose-500 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JavaScript Logic --}}
    <script>
        const admins = @json($admins ?? []);
        const managers = @json($managers ?? []);

        function updateReportingList() {
            const role = document.getElementById('roleSelect').value;
            const div = document.getElementById('reportingDiv');
            const select = document.getElementById('reportingSelect');
            const label = document.getElementById('reportingLabel');

            select.innerHTML = '<option value="">-- NO BOSS (DIRECT) --</option>';

            if (role === 'staff') {
                div.style.display = 'block';
                label.innerText = 'Assign Reporting Boss';
                managers.forEach(m => { select.innerHTML += `<option value="${m.id}">MANAGER: ${m.name}</option>`; });
                admins.forEach(a => { select.innerHTML += `<option value="${a.id}">ADMIN: ${a.name}</option>`; });
            } else if (role === 'manager') {
                div.style.display = 'block';
                label.innerText = 'Assign Senior Admin';
                admins.forEach(a => { select.innerHTML += `<option value="${a.id}">ADMIN: ${a.name}</option>`; });
            } else {
                div.style.display = 'none'; 
            }
        }
        document.addEventListener('DOMContentLoaded', updateReportingList);
    </script>
</x-app-layout>