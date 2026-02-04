<x-layout>
    <div class="max-w-md mx-auto mt-10">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Create Account</h2>
            <p class="text-zinc-400">Join Campus Parking today.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-400 mb-1">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-400 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-400 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-400 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('password_confirmation') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-white text-black py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Sign Up
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-zinc-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-white hover:underline">Log in</a>
        </p>
    </div>
</x-layout>
