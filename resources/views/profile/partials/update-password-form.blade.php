<section>
    <header>
        <h2 class="text-lg font-bold text-white uppercase italic">
            Security <span class="mvs-gold">Update</span>
        </h2>
        <p class="mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- New Password --}}
        <div>
            <x-input-label for="password" :value="__('New Password')" class="text-slate-400 text-[10px] font-bold uppercase mb-1" />
            <x-text-input id="password" name="password" type="password" 
                class="mt-1 block w-full bg-black/30 border-white/10 text-white focus:border-[#c5a043] focus:ring-[#c5a043] rounded-lg" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-slate-400 text-[10px] font-bold uppercase mb-1" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full bg-black/30 border-white/10 text-white focus:border-[#c5a043] focus:ring-[#c5a043] rounded-lg" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-[#c5a043] text-black px-8 py-2.5 rounded-lg text-[10px] font-black uppercase hover:bg-yellow-600 transition-all shadow-lg">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-[10px] font-bold text-emerald-500 uppercase italic"
                >{{ __('Password Updated.') }}</p>
            @endif
        </div>
    </form>
</section>