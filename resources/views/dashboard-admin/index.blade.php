<x-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Admin Dashboard</h1>
                <p class="text-zinc-400">Overview of the parking system.</p>
            </div>
            <div class="text-right">
                <span class="text-zinc-500 text-sm block">System Status</span>
                <span class="text-green-400 font-mono font-bold flex items-center gap-2 justify-end">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Operational
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex flex-col">
                    <p class="text-zinc-400 text-sm font-medium">Total Spots</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['totalSpots'] }}</p>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex flex-col">
                    <p class="text-zinc-400 text-sm font-medium">Available Now</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['availableSpots'] }}</p>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex flex-col">
                    <p class="text-zinc-400 text-sm font-medium">Active Reservations</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['activeReservations'] }}</p>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
                <div class="flex flex-col">
                    <p class="text-zinc-400 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['totalUsers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.parking-spots') }}" class="group bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-white mb-2">Manage Parking Spots</h3>
                        <p class="text-zinc-400 text-sm">Create, edit, or deactivate parking spots.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.reservations') }}" class="group bg-zinc-900 border border-zinc-800 rounded-lg p-6 hover:border-zinc-600 transition-colors">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-white mb-2">Manage Reservations</h3>
                        <p class="text-zinc-400 text-sm">View all reservations and manage cancellations.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layout>