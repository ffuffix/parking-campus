<x-layout>
    <div class="max-w-md mx-auto mt-10">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Welcome back</h2>
            <p class="text-zinc-400">Sign in to your account.</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-6">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-400 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-400 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full bg-black border border-zinc-800 rounded-md py-2 px-3 text-white focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-colors">
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded bg-zinc-900 border-zinc-700 text-white focus:ring-white">
                        <span class="ml-2 text-sm text-zinc-400">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-zinc-400 hover:text-white transition-colors" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-white text-black py-2 rounded-full font-medium hover:bg-zinc-200 transition-colors">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-zinc-500">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-white hover:underline">Sign up</a>
        </p>
    </div>
</x-layout>