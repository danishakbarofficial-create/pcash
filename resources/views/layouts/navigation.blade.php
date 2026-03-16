<nav x-data="{ open: false, userDropdown: false }" class="bg-[#0b0c10] border-b border-white/10 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                {{-- Logo Section --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/mvs-logo.png') }}" class="h-12 w-auto" alt="MVS Logo">
                    </a>
                </div>

                {{-- Navigation Links --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-12 sm:flex h-full">
                    @php
                        $linkBase = "inline-flex items-center px-1 pt-1 text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 h-full border-b-2";
                        $activeClass = "border-[#c5a043] text-[#c5a043]";
                        $inactiveClass = "border-transparent text-white hover:text-[#c5a043] hover:border-[#c5a043]/50";
                        $userRole = Auth::user()->role;
                    @endphp

                    <a href="{{ route('dashboard') }}" class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                        {{ __('Dashboard') }}
                    </a>

                    {{-- Admin Specific Links --}}
                    @if($userRole === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
                            {{ __('Users') }}
                        </a>
                        <a href="{{ route('admin.ledger') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.ledger') ? $activeClass : $inactiveClass }}">
                            {{ __('Ledger') }}
                        </a>
                        <a href="{{ route('admin.addCash') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.addCash') ? $activeClass : $inactiveClass }}">
                            {{ __('Vault') }}
                        </a>
                        {{-- New Reporting Link for Admin --}}
                        <a href="{{ route('admin.reporting') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.reporting') ? $activeClass : $inactiveClass }}">
                            {{ __('Reporting') }}
                        </a>
                    @endif

                    {{-- Staff & Manager Links --}}
                    @if($userRole === 'staff' || $userRole === 'manager')
                        <a href="{{ route('user.wallet') }}" class="{{ $linkBase }} {{ request()->routeIs('user.wallet') ? $activeClass : $inactiveClass }}">
                            <span class="{{ request()->routeIs('user.wallet') ? 'text-[#c5a043]' : 'text-white' }} font-black">{{ __('My Wallet') }}</span>
                        </a>
                        
                        <a href="{{ url('/my-history') }}" class="{{ $linkBase }} {{ request()->is('my-history') ? $activeClass : $inactiveClass }}">
                            {{ __('My History') }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- User Profile Dropdown --}}
            <div class="hidden sm:flex sm:items-center">
                <div class="relative" id="user-dropdown-wrapper">
                    {{-- Alpine.js trigger added to ensure dropdown works --}}
                    <button @click="userDropdown = !userDropdown" id="dropdown-btn" class="flex items-center px-4 py-2 border border-white/10 rounded-xl bg-white/5 hover:bg-white/10 transition-all group focus:outline-none">
                        <div class="flex flex-col text-right me-3">
                            <span class="text-xs font-bold text-white group-hover:text-[#c5a043] transition-colors">{{ Auth::user()->name }}</span>
                            <span class="text-[9px] text-[#c5a043] font-black uppercase tracking-tighter">{{ $userRole }}</span>
                        </div>
                        <div class="h-9 w-9 rounded-lg bg-[#c5a043] flex items-center justify-center text-black font-black text-sm shadow-[0_0_20px_rgba(197,160,67,0.2)]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    {{-- Dropdown Menu (Fixed visibility) --}}
                    <div x-show="userDropdown" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         @click.away="userDropdown = false" 
                         id="dropdown-menu" 
                         class="absolute right-0 mt-2 w-48 bg-[#151921] border border-white/10 shadow-2xl rounded-xl overflow-hidden z-[100]" 
                         style="display: none;">
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-[10px] font-bold uppercase text-white hover:bg-[#c5a043] hover:text-black transition-colors">
                            {{ __('My Profile') }}
                        </a>
                        
                        <div class="border-t border-white/5"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-[10px] font-bold uppercase text-rose-500 hover:bg-rose-500 hover:text-white transition-colors">
                                {{ __('Sign Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile Button --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-slate-400 hover:text-white hover:bg-white/5">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" @click.away="open = false" id="mobile-menu" class="sm:hidden bg-[#0b0c10] border-t border-white/5" style="display: none;">
        <div class="pt-2 pb-3 space-y-1 px-4">
             <a href="{{ route('dashboard') }}" class="block py-2 text-white text-xs font-bold uppercase">Dashboard</a>
             
             @if($userRole === 'admin')
                <a href="{{ route('admin.users.index') }}" class="block py-2 text-white text-xs font-bold uppercase">Users</a>
                <a href="{{ route('admin.ledger') }}" class="block py-2 text-white text-xs font-bold uppercase">Ledger</a>
                <a href="{{ route('admin.reporting') }}" class="block py-2 text-white text-xs font-bold uppercase">Reporting</a>
             @endif

             @if($userRole === 'staff' || $userRole === 'manager')
                <a href="{{ route('user.wallet') }}" class="block py-2 text-[#c5a043] text-xs font-bold uppercase">My Wallet</a>
                <a href="{{ url('/my-history') }}" class="block py-2 text-white text-xs font-bold uppercase">My History</a>
             @endif

             <form method="POST" action="{{ route('logout') }}" class="border-t border-white/5 mt-2 pt-2">
                @csrf
                <button type="submit" class="text-rose-500 text-xs font-bold uppercase">Sign Out</button>
             </form>
        </div>
    </div>
</nav>

{{-- Fallback Script if Alpine fails --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('dropdown-btn');
        const menu = document.getElementById('dropdown-menu');

        if(btn && menu) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Manually toggle if Alpine isn't doing it
                if (menu.style.display === 'none' || menu.classList.contains('hidden')) {
                    menu.style.display = 'block';
                    menu.classList.remove('hidden');
                } else {
                    menu.style.display = 'none';
                }
            });

            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target)) {
                    menu.style.display = 'none';
                }
            });
        }
    });
</script>