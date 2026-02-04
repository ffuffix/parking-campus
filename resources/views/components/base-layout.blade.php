<div>
    <header>
        <h1 class="text-3xl font-bold mb-4">{{ $title }}</h1>
    </header>
    
    {{ $slot }}

    <footer class="mt-8 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} My Application. All rights reserved.
    </footer>
</div>