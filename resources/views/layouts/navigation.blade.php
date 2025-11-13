<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#0a1a3f] to-[#122b73] shadow-md">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between h-20">
            <div class="flex items-center space-x-8">

                <!-- 🟡 Logo -->
                <div class="flex items-center space-x-2 cursor-default select-none">
                    <img src="{{ asset('images/logounib.png') }}" alt="Logo" class="block h-12 w-auto" />
                    <span class="text-white font-semibold text-lg">Surat Menyurat</span>
                </div>

                <!-- 🟦 Navigation Links -->
                <div class="hidden sm:flex space-x-6">
                    <a href="{{ route('dashboard') }}" 
                       class="relative text-white text-sm font-medium transition group">
                        Dashboard
                        <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-white group-hover:w-full transition-all"></span>
                    </a>

                    <a href="{{ route('surat.riwayat') }}" 
                       class="relative text-white text-sm font-medium transition group">
                        Riwayat
                        <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-white group-hover:w-full transition-all"></span>
                    </a>

                    <!-- 🧩 Tambahan Menu -->
                    <a href="{{ route('about') }}" 
                       class="relative text-white text-sm font-medium transition group">
                        About Us
                        <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-white group-hover:w-full transition-all"></span>
                    </a>

                    <a href="{{ route('contact') }}" 
                       class="relative text-white text-sm font-medium transition group">
                        Contact Us
                        <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-white group-hover:w-full transition-all"></span>
                    </a>
                </div>
            </div>

            <!-- 🧑‍💼 User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-600 rounded-md transition">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-2 w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- 🍔 Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="text-white hover:bg-blue-600 p-2 rounded-md transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
