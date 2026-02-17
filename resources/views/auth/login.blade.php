<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Messages -->
            @if ($errors->any())
                <div class="toast-enterprise-error flex items-start gap-3 mb-6 px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-6 w-6 mt-0.5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2M9 1a8 8 0 018 8v10a1 1 0 01-1 1H4a1 1 0 01-1-1V9a8 8 0 018-8z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-sm">Error</h3>
                        <div class="text-sm">{{ $errors->first() }}</div>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="toast-enterprise-success flex items-start gap-3 mb-6 px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-emerald-600 shrink-0 h-6 w-6 mt-0.5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-sm">Success</h3>
                        <div class="text-sm">{{ session('status') }}</div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="card-enterprise ">
                <div class="p-8">
                    <!-- Logo -->
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <div class="w-40 h-40 rounded-xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
                                <a href="{{ route('welcome') }}">
                                <img src="{{ asset('images/logo.jpg') }}" alt="Golden Gate College Logo" class="w-full h-full object-cover" />
                                </a>
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-1">Golden Gate College</h1>
                        <p class="text-sm text-slate-500">Hospital Informatics System</p>
                    </div>

                    <form method="POST" action="{{ route('login', [], false) }}" class="space-y-6">
                        @csrf

                        <!-- Badge ID -->
                        <div class="form-control">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Badge ID</label>
                            <input
                                id="badge_id"
                                class="input-enterprise w-full py-3 text-base"
                                type="text"
                                name="badge_id"
                                :value="old('badge_id')"
                                required
                                autofocus
                                autocomplete="off"
                                inputmode="numeric"
                            />
                            <x-input-error :messages="$errors->get('badge_id')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="form-control">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                            <input
                                id="password"
                                class="input-enterprise w-full py-3 text-base"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                            />
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
                        <div class="form-control pt-4">
                            <button type="submit" class="btn-enterprise-primary w-full py-3 text-base font-bold">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
