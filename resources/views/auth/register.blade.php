<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="form-control w-full">
            <label class="label" for="name">
                <span class="label-text">{{ __('Name') }}</span>
            </label>
            <input id="name" class="input input-bordered w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">{{ __('Email') }}</span>
            </label>
            <input id="email" class="input input-bordered w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">{{ __('Password') }}</span>
            </label>
            <input id="password" class="input input-bordered w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-control w-full">
            <label class="label" for="password_confirmation">
                <span class="label-text">{{ __('Confirm Password') }}</span>
            </label>
            <input id="password_confirmation" class="input input-bordered w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="link link-hover text-sm" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
