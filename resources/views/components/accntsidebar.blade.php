<div class="drawer-side z-20">
    <label for="my-drawer-2" class="drawer-overlay"></label>
    <aside class="menu p-4 w-72 min-h-full bg-neutral text-neutral-content flex flex-col">

        <!-- Brand -->
        <div class="mb-8 px-4 pt-4">
            <div class="flex items-center gap-4">
                <div class="avatar">
                    <div class="w-12 rounded-full ring ring-primary ring-offset-neutral ring-offset-2">
                        <img src="{{ asset('images/chansey.jpg') }}" alt="Chansey Logo" class="object-cover" />
                    </div>
                </div>
                <div>
                    <div class="text-3xl font-black text-white tracking-tighter leading-none">
                        Chansey
                    </div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mt-1">
                        Accounting Console
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <ul class="space-y-2 flex-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('accountant.dashboard') }}"
                    class="{{ request()->routeIs('accountant.dashboard') ? 'active bg-primary text-white font-bold' : 'font-medium hover:bg-neutral-focus transition-all' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            <!--Discharge History -->
            <li>
                <a href="{{ route('accountant.history') }}"
                    class="{{ request()->routeIs('accountant.history') ? 'active bg-primary text-white font-bold' : 'font-medium hover:bg-neutral-focus transition-all' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Billing History
                </a>
            </li>

            <!--Hospital Fees -->
            <li>
                <a href="{{ route('accountant.fees.index') }}"
                    class="{{ request()->routeIs('accountant.fees.index') ? 'active bg-primary text-white font-bold' : 'font-medium hover:bg-neutral-focus transition-all' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Hospital Fees
                </a>
            </li>

            <!--Hospital Fees -->
            <li>
                <a href="{{ route('accountant.billinginfo.index') }}"
                    class="{{ request()->routeIs('accountant.billinginfo.index') ? 'active bg-primary text-white font-bold' : 'font-medium hover:bg-neutral-focus transition-all' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Billing Information
                </a>
            </li>
        </ul>


        <!-- User Footer  -->
        <div class="border-t border-gray-700 pt-4 mt-4 space-y-3">
            <div class="flex items-center gap-3 px-2">
                <div class="avatar placeholder shrink-0">
                    <div class="bg-secondary text-secondary-content rounded-full w-10 flex items-center justify-center">
                        <span class="font-bold text-sm text-white">{{ Auth::user()->initials }}</span>
                    </div>
                </div>
                <div class="overflow-hidden flex-1">
                    <div class="font-bold text-sm text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-400 truncate">
                        {{ Auth::user()->nurse->designation ?? 'Nurse' }} ({{ Auth::user()->badge_id }})
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
            <button onclick="document.getElementById('logout-form').submit();" class="btn btn-outline btn-error btn-sm w-full gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </div>
    </aside>
</div>