<section>
    <header>
        <h2 class="text-lg font-bold text-white uppercase italic">
            Profile <span class="mvs-gold">Information</span>
        </h2>

        <p class="mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name Field --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-slate-400 text-[10px] font-bold uppercase mb-1" />
            <x-text-input id="name" name="name" type="text" 
                class="mt-1 block w-full bg-black/30 border-white/10 text-white focus:border-[#c5a043] focus:ring-[#c5a043] rounded-lg" 
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-rose-500 text-xs" :messages="$errors->get('name')" />
        </div>

        {{-- Email Field --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-slate-400 text-[10px] font-bold uppercase mb-1" />
            <x-text-input id="email" name="email" type="email" 
                class="mt-1 block w-full bg-black/30 border-white/10 text-white focus:border-[#c5a043] focus:ring-[#c5a043] rounded-lg" 
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-rose-500 text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-[10px] font-bold mt-2 text-slate-400 uppercase">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="ml-2 underline text-[#c5a043] hover:text-white transition-colors">
                            {{ __('Re-send verification email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-[10px] text-emerald-500 uppercase italic">
                            {{ __('A new verification link has been sent.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-[#c5a043] text-black px-8 py-2.5 rounded-lg text-[10px] font-black uppercase hover:bg-yellow-600 transition-all shadow-lg">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-[10px] font-bold text-emerald-500 uppercase italic"
                >{{ __('Changes Saved Successfully.') }}</p>
            @endif
        </div>
    </form>
</section>