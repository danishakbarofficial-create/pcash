<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New User / Project Head</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Full Name</label>
                            <input type="text" name="name" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Email Address</label>
                            <input type="email" name="email" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">System Role</label>
                            <select name="role" class="w-full border-gray-200 rounded-lg shadow-sm">
                                <option value="staff">Staff (Request Submitter)</option>
                                <option value="manager">Project Head (Approver)</option>
                                <option value="admin">System Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Assign Project (Cost Center)</label>
                            <input type="text" name="project_name" placeholder="e.g. Riyadh Metro" class="w-full border-gray-200 rounded-lg shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full border-gray-200 rounded-lg shadow-sm">
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition uppercase tracking-widest text-xs">
                            Register User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>