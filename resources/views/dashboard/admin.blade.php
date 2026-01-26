<!-- Include Alpine.js in your layout head if not already -->
<script src="//unpkg.com/alpinejs" defer></script>

<x-app-layout>
    <div x-data="{ sidebarOpen: true, hoverSidebar: false }" class="flex h-screen">

        <!-- Sidebar -->
        <div
            @mouseenter="!sidebarOpen ? hoverSidebar = true : ''"
            @mouseleave="hoverSidebar = false"
            :class="sidebarOpen || hoverSidebar ? 'w-60' : 'w-16'"
            class="bg-gray-800 text-white h-full transition-all duration-300 flex flex-col relative">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4">
                <span x-show="sidebarOpen || hoverSidebar" class="text-lg font-bold transition">Admin Panel</span>
                <span x-show="!sidebarOpen && !hoverSidebar" class="text-lg font-bold flex justify-center items-center">🏢</span>
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="text-white bg-gray-700 hover:bg-gray-600 p-2 rounded focus:outline-none shadow-lg ml-2">
                    <span x-show="sidebarOpen">❮</span>
                    <span x-show="!sidebarOpen">❯</span>
                </button>
            </div>

            <!-- Sidebar Links -->
            <nav class="mt-4 flex-1 flex flex-col gap-1 overflow-hidden">
                <template x-if="sidebarOpen || hoverSidebar">
                    <div class="flex flex-col gap-1">
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Owners List</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Customers List</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Warehouses</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Products / Inventory</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Orders</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Reports</a>
                        <a href="#" class="block py-2 px-3 hover:bg-gray-700 rounded transition">Settings</a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </template>

                <!-- Collapsed icons only with tooltips -->
                <template x-if="!sidebarOpen && !hoverSidebar">
                    <div class="flex flex-col gap-4 items-center mt-4">
                        <div class="relative group">
                            <span>👤</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Owners List
                            </span>
                        </div>
                        <div class="relative group">
                            <span>🧑‍🤝‍🧑</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Customers List
                            </span>
                        </div>
                        <div class="relative group">
                            <span>🏭</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Warehouses
                            </span>
                        </div>
                        <div class="relative group">
                            <span>📦</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Products / Inventory
                            </span>
                        </div>
                        <div class="relative group">
                            <span>📝</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Orders
                            </span>
                        </div>
                        <div class="relative group">
                            <span>📊</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Reports
                            </span>
                        </div>
                        <div class="relative group">
                            <span>⚙️</span>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Settings
                            </span>
                        </div>
                        <div class="relative group">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">🔒</button>
                            </form>
                            <span
                                class="absolute left-16 top-1/2 -translate-y-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Logout
                            </span>
                        </div>
                    </div>
                </template>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-4">Welcome Admin!</h1>
            <p>Manage the complete Smart Warehouse System from here.</p>
        </div>

    </div>
</x-app-layout>
