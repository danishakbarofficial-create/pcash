<x-app-layout>
    <style>
        .mvs-input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 0.8rem !important;
        }
        .mvs-input:focus {
            border-color: #c5a043 !important;
            box-shadow: 0 0 0 2px rgba(197, 160, 67, 0.2) !important;
            outline: none;
        }
        /* Style for select options to ensure visibility */
        select.mvs-input option {
            background: #11141b;
            color: white;
        }
    </style>

    <div class="py-12 bg-black min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 font-bold rounded-2xl flex items-center italic">
                    <span class="mr-3">✅</span> {{ session('success') }}
                </div>
            @endif

            {{-- Add Project Form --}}
            <div class="bg-[#11141b] overflow-hidden shadow-2xl rounded-[2rem] border border-white/5 mb-8">
                <div class="p-8">
                    <h2 class="font-black text-xl text-white italic uppercase tracking-tighter mb-6">
                        Add New <span class="text-[#c5a043]">Project / Site</span>
                    </h2>
                    
                    <form action="{{ route('admin.projects.store') }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex-1">
                                <label class="text-[10px] text-gray-500 uppercase font-bold mb-1 block ml-2">Project Name</label>
                                <input type="text" name="name" placeholder="e.g. Riyadh Metro" required 
                                       class="mvs-input w-full text-sm font-bold placeholder:text-slate-700">
                            </div>
                            <div class="flex-1">
                                <label class="text-[10px] text-gray-500 uppercase font-bold mb-1 block ml-2">Site Location</label>
                                <input type="text" name="location" placeholder="e.g. District 5, Riyadh" 
                                       class="mvs-input w-full text-sm font-bold placeholder:text-slate-700">
                            </div>
                            <div class="flex-1">
                                <label class="text-[10px] text-gray-500 uppercase font-bold mb-1 block ml-2">Project Manager</label>
                                {{-- Updated: Text input replaced with Select Dropdown --}}
                                <select name="manager_name" class="mvs-input w-full text-sm font-bold">
                                    <option value="">Select Manager</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->name }}">
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="bg-[#c5a043] text-black font-black py-3 px-12 rounded-xl uppercase text-[10px] tracking-widest hover:bg-white transition-all">
                                Add Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Projects List --}}
            <div class="bg-[#11141b] overflow-hidden shadow-2xl rounded-[2rem] border border-white/5">
                <div class="p-8">
                    <h2 class="font-black text-xl text-white italic uppercase tracking-tighter mb-6">
                        Active <span class="text-[#c5a043]">Projects</span>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                    <th class="px-6 py-2">ID</th>
                                    <th class="px-6 py-2">Project Details</th>
                                    <th class="px-6 py-2">Location</th>
                                    <th class="px-6 py-2">Manager</th>
                                    <th class="px-6 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-all">
                                        <td class="px-6 py-4 rounded-l-2xl text-slate-400 font-mono text-xs">#{{ $project->id }}</td>
                                        <td class="px-6 py-4 text-white font-bold italic">{{ strtoupper($project->name) }}</td>
                                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $project->location ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="bg-[#c5a043]/10 text-[#c5a043] text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                                                {{ $project->manager_name ?? 'Not Assigned' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 rounded-r-2xl text-right">
                                            <form action="{{ route('admin.projects.delete', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500/50 hover:text-red-500 transition-colors text-xs font-black uppercase tracking-widest">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10 text-slate-600 italic font-bold">No projects found. Add your first project above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>