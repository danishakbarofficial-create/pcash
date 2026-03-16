<x-app-layout>
    <style>
        /* Dashboard matching styles with high specificity */
        .mvs-card { 
            background: #151921 !important; 
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 16px !important;
        }
        .mvs-gold { color: #c5a043 !important; }
        
        /* Force input visibility */
        .mvs-card input {
            background-color: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            padding: 10px !important;
            width: 100% !important;
        }

        .mvs-card input:focus {
            border-color: #c5a043 !important;
            ring: 2px #c5a043 !important;
            outline: none !important;
        }

        /* Fix Label Visibility */
        .mvs-card label {
            color: #94a3b8 !important; /* Slate-400 */
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin-bottom: 4px !important;
            display: block !important;
        }

        /* Success/Saved Text */
        .text-gray-600 { color: #10b981 !important; font-size: 10px !important; font-weight: 800 !important; text-transform: uppercase !important; }

        .mvs-btn-gold {
            background: #c5a043 !important;
            color: black !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            transition: all 0.3s ease !important;
        }
        .mvs-btn-gold:hover { background: #e2c06d !important; transform: translateY(-1px); }
    </style>

    <div class="py-12 bg-[#0b0c10] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Header --}}
            <div class="mb-10 flex flex-col items-start">
                <h2 class="text-2xl font-black tracking-tighter text-white italic uppercase">
                    Account <span class="mvs-gold">Settings</span>
                </h2>
                <div class="h-1 w-20 bg-gradient-to-r from-[#c5a043] to-transparent mt-1"></div>
                <p class="text-slate-500 text-[10px] font-bold tracking-[0.2em] uppercase mt-2">Personal Security & Profile Management</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Update Profile Information --}}
                <div class="p-8 mvs-card shadow-2xl transition-all hover:border-white/10">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="p-8 mvs-card shadow-2xl transition-all hover:border-white/10">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete User Account --}}
                <div class="p-8 mvs-card border-t-4 border-rose-500/30 shadow-2xl md:col-span-2">
                    <div class="max-w-xl">
                        <h2 class="text-lg font-bold text-white uppercase italic mb-4">
                            Danger <span class="text-rose-500">Zone</span>
                        </h2>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>