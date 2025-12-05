<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Badge ID -->
        <div class="form-control w-full">
            <label class="label" for="badge_id">
                <span class="label-text font-semibold">{{ __('Badge ID') }}</span>
            </label>
            <input id="badge_id" class="input input-bordered input-primary w-full" type="text" name="badge_id" :value="old('badge_id')" required autofocus autocomplete="off" inputmode="numeric" />
            <x-input-error :messages="$errors->get('badge_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text font-semibold">{{ __('Password') }}</span>
            </label>
            <input id="password" class="input input-bordered input-primary w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-control">
            <label class="label cursor-pointer justify-start gap-2">
                <input id="remember_me" type="checkbox" class="checkbox checkbox-primary" name="remember">
                <span class="label-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit" class="btn btn-primary w-full shadow-lg">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>