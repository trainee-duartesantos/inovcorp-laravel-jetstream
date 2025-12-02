@extends('layouts.app')

@push('styles')
    {{-- CSS específico do dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
    {{-- JS específico do dashboard (export CSV, etc.) --}}
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
@endpush

@section('content')
    <div class="py-6 bg-white-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TÍTULO E CARDS DE MENU --}}
            <div class="flex flex-col items-center w-full gap-8 mb-8 bg-gray-800 py-10 rounded-xl shadow-lg">
                <h1 class="text-5xl font-bold text-center text-white biblioteca">
                    {{ __('Biblioteca') }}
                </h1>

                <div class="flex flex-wrap justify-center gap-6" id="mainTabs">
                    <div class="hover-card w-48" onclick="showTab('livros')">
                        <div class="card-body text-center p-6">
                            <div class="text-4xl mb-3">📚</div>
                            <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Livros</h3>
                        </div>
                    </div>

                    <div class="hover-card w-48" onclick="showTab('autores')">
                        <div class="card-body text-center p-6">
                            <div class="text-4xl mb-3">✍️</div>
                            <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Autores</h3>
                        </div>
                    </div>

                    <div class="hover-card w-48" onclick="showTab('editoras')">
                        <div class="card-body text-center p-6">
                            <div class="text-4xl mb-3">🏢</div>
                            <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Editoras</h3>
                        </div>
                    </div>
                </div>
                
            </div>
            

            {{-- 🔹 Menus Públicos dentro do Dashboard --}}
            <div class="flex items-center justify-center gap-6 my-6">
                <a href="{{ route('livros.index') }}" class="btn btn-outline btn-secondary">
                    📚 Catálogo
                </a>
                <a href="{{ route('requisicoes.index') }}" class="btn btn-outline btn-secondary">
                    ✨ Minhas Requisições
                </a>
                <a href="{{ route('admin.requisicoes.index') }}" class="btn btn-outline btn-secondary">
                    🔁 Gestão de Requisições
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline btn-secondary">
                    ⭐ Gestão de Reviews
                </a>
            </div>
            {{-- ========== TAB LIVROS ========== --}}
            <div id="tab-livros">
                <div class="card bg-white shadow-lg mb-6">
                    <div class="card-body">
                        {{-- Barra de pesquisa + limpar --}}
                        <div class="flex flex-col sm:flex-row gap-4 mb-4 items-center">
                            <div class="flex-1 relative" style="padding: 1rem;">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text"
                                           class="grow"
                                           placeholder="Pesquisar por Nome, ISBN, Autor..."
                                           id="search-livros"
                                           onkeyup="debouncedSearch('livros', this.value)"/>
                                </label>
                                <div id="suggestions-livros"
                                     class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden">
                                </div>
                            </div>
                            <div class="flex justify-center sm:justify-start">
                                <button class="btn-limpar" onclick="clearSearch('livros')">
                                    Limpar
                                </button>
                            </div>
                        </div>

                        {{-- Filtros (Exemplo estático, podes ligar a dados reais depois) --}}
                        <div class="flex justify-center mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl px-4">
                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Editora</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200"
                                            onchange="filterTable('livros', this.value, 'editora')">
                                        <option value="">Todas as editoras</option>
                                        <option value="Editora Leya">Editora Leya</option>
                                        <option value="Porto Editora">Porto Editora</option>
                                        <option value="Penguin Random House">Penguin Random House</option>
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Preço</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200"
                                            onchange="filterTable('livros', this.value, 'preco')">
                                        <option value="">Qualquer preço</option>
                                        <option value="0-20">€0 - €20</option>
                                        <option value="20-40">€20 - €40</option>
                                        <option value="40+">€40+</option>
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Autor</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200"
                                            onchange="filterTable('livros', this.value, 'autor')">
                                        <option value="">Todos os autores</option>
                                        <option value="J.R.R. Tolkien">J.R.R. Tolkien</option>
                                        <option value="George Orwell">George Orwell</option>
                                        <option value="Miguel de Cervantes">Miguel de Cervantes</option>
                                        <option value="Umberto Eco">Umberto Eco</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm" style="padding: 1rem;">
                            <span id="result-count-livros" class="text-base-content/70">
                                Mostrando todos os livros
                            </span>
                            <div class="flex gap-2">
                                <span class="badge badge-ghost" id="active-filters-livros"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabela + Export CSV --}}
                <div class="card bg-white shadow-xl">
                    <div class="card-body">
                        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6 w-full flex-between-header">
                            <div class="flex items-center flex-shrink-0">
                                <h2 class="card-title text-2xl font-bold text-gray-800 whitespace-nowrap">
                                    📚 Gestão de Livros
                                </h2>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto justify-end">
                                {{-- Botão Exportar CSV --}}
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('exportar.livros.csv') }}"
                                       class="btn"
                                       id="btn-exportar-csv">
                                        <span>📊 Exportar CSV</span>
                                    </a>
                                @endif

                                <div class="lg:hidden text-center sm:text-left w-full sm:w-auto">
                                    <span class="text-sm text-gray-500">↔ Deslize horizontalmente</span>
                                </div>
                            </div>
                        </div>

                        {{-- Versão Desktop --}}
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="table table-zebra" id="table-livros">
                                <thead>
                                    <tr>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 0)">ID <span id="sort-livros-0">↕</span></th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 1)">ISBN <span id="sort-livros-1">↕</span></th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 2)">Nome <span id="sort-livros-2">↕</span></th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 3)">Editora <span id="sort-livros-3">↕</span></th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 4)">Autores <span id="sort-livros-4">↕</span></th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 5)">Bibliografia <span id="sort-livros-5">↕</span></th>
                                        <th>Capa</th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 6)">Preço <span id="sort-livros-6">↕</span></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-livros">
                                    {{-- Dados preenchidos via JavaScript --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Versão Mobile --}}
                        <div class="lg:hidden" id="mobile-livros-container">
                            <div id="mobile-livros-list">
                                {{-- Cards móveis via JS --}}
                            </div>
                        </div>

                        <div id="empty-state-livros" class="text-center py-8 hidden">
                            <div class="text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-lg mb-2">Nenhum livro encontrado</p>
                                <p class="text-sm mb-4">Tente ajustar os filtros ou termos de pesquisa</p>
                                <button class="btn btn-primary" onclick="clearSearch('livros')">Limpar pesquisa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== TAB AUTORES ========== --}}
            <div id="tab-autores" class="card bg-white shadow-xl hidden">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title text-2xl font-bold text-gray-800">✍️ Gestão de Autores</h2>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
                        <div class="flex-1">
                            <label class="input input-bordered flex items-center gap-2">
                                <input type="text" class="grow"
                                       placeholder="Pesquisar autores..."
                                       onkeyup="searchTable('autores', this.value)"/>
                            </label>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="table-autores" style="text-align:center">
                            <thead>
                                <tr>
                                    <th class="cursor-pointer hover:bg-base-200"
                                        onclick="sortTable('autores', 0)">Nome <span id="sort-autores-0">↕</span></th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-autores">
                                {{-- Dados via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ========== TAB EDITORAS ========== --}}
            <div id="tab-editoras" class="card bg-white shadow-xl hidden">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title text-2xl font-bold text-gray-800">🏢 Gestão de Editoras</h2>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
                        <div class="flex-1">
                            <label class="input input-bordered flex items-center gap-2">
                                <input type="text" class="grow"
                                       placeholder="Pesquisar editoras..."
                                       onkeyup="searchTable('editoras', this.value)"/>
                            </label>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="table-editoras" style="text-align:center">
                            <thead>
                                <tr>
                                    <th class="cursor-pointer hover:bg-base-200"
                                        onclick="sortTable('editoras', 0)">Nome <span id="sort-editoras-0">↕</span></th>
                                    <th>Logótipo</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-editoras">
                                {{-- Dados via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer sm:footer-horizontal footer-center">
        <aside>
            <p>Copyright © All right reserved by Inovcorp Group</p>
        </aside>
    </footer>
    <script>
        window.DATA = {
            livros: @json($livros),
            autores: @json($autores),
            editoras: @json($editoras),
            storageBaseUrl: "{{ asset('storage') }}"
        };

        function showTab(tab) {
            ['livros', 'autores', 'editoras', 'requisicoes'].forEach(id => {
                const el = document.getElementById('tab-' + id);
                if (el) el.classList.add('hidden');
            });

            const activeTab = document.getElementById('tab-' + tab);
            if (activeTab) activeTab.classList.remove('hidden');
        }

        // Abrir tab Livros por defeito
        document.addEventListener("DOMContentLoaded", () => {
            @if(auth()->user()->isAdmin())
                showTab('livros');
            @endif
        });
        </script>

@endsection
