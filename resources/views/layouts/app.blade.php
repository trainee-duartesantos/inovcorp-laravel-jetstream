<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @stack('styles')
        @stack('scripts')


    </head>
    <body class="font-sans antialiased">
        <x-banner />
            <nav class="bg-gray-900 text-gray-100 px-6 py-3 flex justify-between items-center shadow-lg">
                <div class="flex gap-6 items-center">
                    <a href="{{ route('livros.index') }}" class="hover:text-yellow-300 font-semibold">
                        📚 Catálogo
                    </a>

                    <a href="{{ route('requisicoes.index') }}" class="hover:text-yellow-300 font-semibold">
                        🔁 Minhas Requisições
                    </a>
                </div>

                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline border-yellow-300 hover:bg-yellow-300 hover:text-black">
                            🚪 Sair
                        </button>
                    </form>
                </div>
            </nav>


        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-800 shadow-xl">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>

        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
