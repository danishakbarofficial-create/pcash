<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0b0c10; color: #e2e8f0; }
        .mvs-card { background: #151921; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; }
        .form-input { 
            background: rgba(0, 0, 0, 0.2); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            color: white; 
            border-radius: 12px; 
            padding: 12px 16px;
            font-size: 13px;
            transition: all 0.3s;
        }
        .form-input:focus { border-color: #c5a043; box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.1); outline: none; }
        .mvs-gold-btn { background: #c5a043; color: black; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; cursor: pointer; }
        .mvs-gold-btn:hover { background: #e2b85a; transform: translateY(-1px); }
    </style>

    <div class="min-h-screen py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-white italic uppercase">Edit <span class="text-[#c5a043]">User Account</span></h2>
                    <p class="text-slate-500 text-[10px] font-bold tracking-widest uppercase mt-1">Management Console — Security & Roles</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-extrabold text-slate-400 hover:text-white uppercase tracking-widest transition">
                    &larr; Back to Directory
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border-l-4 border-rose-500 rounded-r-xl">
                    <ul class="list-disc ml-5 text-[11px] text-rose-200/70 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mvs-card p-8 shadow-2xl">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full form-input" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full form-input opacity-70" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Operational Role</label>
                            <select name="role" class="w-full form-input font-bold appearance-none">
                                <option value="staff" class="bg-[#151921]" {{ $user->role == 'staff' ? 'selected' : '' }}>STAFF (Submitter)</option>
                                <option value="manager" class="bg-[#151921]" {{ $user->role == 'manager' ? 'selected' : '' }}>MANAGER (Reviewer)</option>
                                <option value="admin" class="bg-[#151921]" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMIN (Approver)</option>
                            </select>
                        </div>

                        {{-- NEW: Assign Reporting Boss Dropdown --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Assign Reporting Boss</label>
                            <select name="reporting_to" class="w-full form-input font-bold appearance-none">
                                <option value="" class="bg-[#151921]">-- NO BOSS (DIRECT TO ADMIN) --</option>
                                @foreach($managers as $m)
                                    <option value="{{ $m->id }}" class="bg-[#151921]" {{ $user->reporting_to == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }} ({{ $m->project_name ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-slate-600 mt-2 font-bold uppercase tracking-widest">Select the manager who will approve this user's requests.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Project Name</label>
                            <input type="text" name="project_name" value="{{ old('project_name', $user->project_name) }}" class="w-full form-input">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Cost Center</label>
                            <input type="text" name="cost_center" value="{{ old('cost_center', $user->cost_center) }}" class="w-full form-input text-[#c5a043]">
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-white/5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">New Password</label>
                                <input type="password" name="password" class="w-full form-input" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full form-input" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-slate-500 hover:text-rose-500 uppercase tracking-widest transition">
                            Cancel
                        </a>
                        <button type="submit" class="mvs-gold-btn px-10 py-3.5 rounded-xl text-[11px]">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>