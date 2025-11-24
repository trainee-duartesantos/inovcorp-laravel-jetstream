<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Biblioteca</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles')
</head>

<body class="bg-base-200 min-h-screen">
<div class="flex">
    @if(auth()->check() && auth()->user()->isAdmin())
    {{-- SIDEBAR --}}
    <div id="sidebar" class="bg-base-300 h-screen p-4 transition-all duration-300 w-64 overflow-hidden">
    
        {{-- LOGO --}}
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('storage/app/public/images/inovcorp-logo.png') }}" class="h-12 w-auto rounded-lg">
            <span class="font-bold text-lg" id="sidebar-label">Inovcorp</span>
        </div>

        {{-- MENU --}}
        <ul class="menu space-y-2 text-base-content">
            <li class="text-lg font-bold mb-4 flex items-center gap-2">
                <img src="{{ asset('storage/app/public/images/inovcorp-logo.png') }}" alt="Inovcorp" class="w-8 h-8 object-contain rounded">
                <span>Biblioteca</span>
            </li>

            <li>
                <a href="{{ route('admin.livros') }}"
                class="{{ request()->routeIs('admin.livros') ? 'active bg-base-100 font-semibold' : '' }}">
                    📘 Gestão de Livros
                </a>
            </li>

            <li>
                <a href="{{ route('admin.autores') }}"
                class="{{ request()->routeIs('admin.autores') ? 'active bg-base-100 font-semibold' : '' }}">
                    ✍️ Gestão de Autores
                </a>
            </li>

            <li>
                <a href="{{ route('admin.editoras') }}"
                class="{{ request()->routeIs('admin.editoras') ? 'active bg-base-100 font-semibold' : '' }}">
                    🏢 Gestão de Editoras
                </a>
            </li>

            <li>
                <a href="{{ route('admin.requisicoes.index') }}"
                class="{{ request()->routeIs('admin.requisicoes.index') ? 'active bg-base-100 font-semibold' : '' }}">
                    🔁 Requisições
                </a>
            </li>

        </ul>
    </div>
    @endif

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- NAVBAR --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <nav class="navbar bg-base-100 shadow-md px-6">
            <button id="toggleSidebar" class="btn btn-ghost">
                <i class="bi bi-layout-sidebar-inset text-xl"></i> {{-- Bootstrap Icons --}}
            </button>

            <div class="flex-1">
                <h2 class="text-xl font-bold">Painel de Administração</h2>
            </div>
        
            {{-- UTILIZADOR --}}
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" />
                    </div>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline">
                                Sair
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        @endif
        {{-- CONTEÚDO --}}
        <main class="p-6 w-full">
            @if (isset($slot))
                {{-- Usado pelos componentes Livewire com #[Layout('layouts.admin')] --}}
                {{ $slot }}
            @else
                {{-- Usado pelas views normais com @extends/@section --}}
                @yield('content')
            @endif
        </main>





    </div>

</div>


{{-- SLIM SIDEBAR SCRIPT --}}
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        let sidebar = document.getElementById('sidebar');
        let label = document.getElementById('sidebar-label');

        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        label.classList.toggle('hidden');

        // Esconde texto das entradas do menu
        document.querySelectorAll('#sidebar ul li a').forEach(item => {
            item.classList.toggle('text-xs');
            item.classList.toggle('truncate');
        });
    });

</script>

@livewireScripts
@stack('scripts')
</body>
</html>
