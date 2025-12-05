<x-guest-layout>
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-2">
        @csrf

        <!-- Badge ID -->
        <div class="form-control">
            <x-input-label for="badge_id" :value="__('Badge ID')" class="label label-text font-semibold" />
            <x-text-input id="badge_id" class="input input-bordered input-primary mt-1 w-full" type="text" name="badge_id" :value="old('badge_id')" required autofocus autocomplete="off" inputmode="numeric" />
            <x-input-error :messages="$errors->get('badge_id')" class="label label-text-alt text-error mt-1" />
        </div>

        <!-- Password -->
        <div class="form-control">
            <x-input-label for="password" :value="__('Password')" class="label label-text font-semibold" />
            <x-text-input id="password" class="input input-bordered input-primary mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="label label-text-alt text-error mt-1" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="label cursor-pointer gap-2">
                <input id="remember_me" type="checkbox" class="checkbox checkbox-primary" name="remember">
                <span class="label-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit" class="btn btn-primary btn-lg w-full shadow-lg">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>