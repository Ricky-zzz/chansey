<div class="drawer-side z-20">
    <label for="my-drawer-2" class="drawer-overlay"></label>
    <aside class="menu w-72 h-screen sidebar-enterprise flex flex-col relative">
        <!-- Close Button (Mobile) -->
        <label for="my-drawer-2" class="btn btn-square btn-ghost absolute top-4 right-4 lg:hidden z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </label>

        <!-- Brand -->
        <div class="mb-8 px-4 pt-4 pb-2 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Golden Gate Academy Logo" class="w-8 h-8 rounded-md object-cover" />
                </div>
                <div>
                    <div class="text-lg font-bold text-slate-800 tracking-tight leading-none">
                        Golden Gate Academy
                    </div>
                    <div class="text-[10px] font-semibold text-emerald-600 uppercase tracking-[0.15em] mt-0.5">
                        Physician Console
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <ul class="space-y-1 overflow-y-scroll flex-1 px-4">
            <li class="px-3 mb-2">
                <span class="sidebar-section-title">Main Menu</span>
            </li>

            <!-- Dashboard -->
            <li>
                <a href="{{ route('physician.dashboard') }}"
                    class="{{ request()->routeIs('physician.dashboard') ? 'sidebar-link-active' : 'sidebar-link' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            <!-- My Patients -->
            <li>
                <a href="{{ route('physician.mypatients.index') }}"
                    class="{{ request()->routeIs('physician.mypatients.index') ? 'sidebar-link-active' : 'sidebar-link' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    My Patients
                </a>
            </li>

            <!-- My Appointments -->
            <li>
                <a href="{{ route('physician.appointments.index') }}"
                    class="{{ request()->routeIs('physician.appointments.index') ? 'sidebar-link-active' : 'sidebar-link' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    My Appointments
                </a>
            </li>

            <!-- Manage Slots -->
            <li>
                <a href="{{ route('physician.slots.index') }}"
                    class="{{ request()->routeIs('physician.slots.*') ? 'sidebar-link-active' : 'sidebar-link' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Manage Slots
                </a>
            </li>
        </ul>

        <!-- User Footer -->
        <div class="border-t border-slate-200 pt-4 space-y-3 px-4 pb-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <span class="font-bold text-sm">{{ Auth::user()->initials }}</span>
                </div>
                <div class="overflow-hidden flex-1">
                    <div class="font-semibold text-sm text-slate-700 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500 truncate">
                        {{ Auth::user()->physician->department->name}} ({{ Auth::user()->badge_id }})
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
            <button onclick="document.getElementById('logout-form').submit();" class="btn-enterprise-danger w-full text-xs gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </div>
    </aside>
</div>
