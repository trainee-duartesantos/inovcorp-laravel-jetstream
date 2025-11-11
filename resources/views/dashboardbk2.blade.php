<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- MENUS COM TABS FUNCIONAIS -->
            <div class="bg-base-100 rounded-lg shadow-lg mb-8">
                <div class="tabs tabs-boxed bg-base-200 p-2" id="mainTabs">
                    <a class="tab tab-active" onclick="showTab('livros')">📚 Livros</a>
                    <a class="tab" onclick="showTab('autores')">✍️ Autores</a>
                    <a class="tab" onclick="showTab('editoras')">🏢 Editoras</a>
                </div>
            </div>

            <!-- ========== SEARCH AVANÇADA - LIVROS ========== -->
            <div id="tab-livros">
                <!-- CARD DE PESQUISA AVANÇADA -->
                <div class="card bg-base-100 shadow-lg mb-6">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">🔍 Pesquisa Avançada - Livros</h3>
                        
                        <!-- BARRA DE PESQUISA PRINCIPAL -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                            <div class="flex-1 relative">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" 
                                           class="grow" 
                                           placeholder="Pesquisar por Nome, ISBN, Autor..." 
                                           id="search-livros"
                                           onkeyup="debouncedSearch('livros', this.value)"/>
                                    
                                </label>
                                <!-- SUGESTÕES -->
                                <div id="suggestions-livros" class="absolute z-10 w-full mt-1 bg-base-100 border rounded-lg shadow-lg hidden"></div>
                            </div>
                            <button class="btn btn-outline" onclick="clearSearch('livros')">
                                Limpar
                            </button>
                        </div>

                        <!-- FILTROS AVANÇADOS -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <select class="select select-bordered" onchange="filterTable('livros', this.value, 'editora')">
                                <option value="">Todas as editoras</option>
                                <option value="Editora Leya">Editora Leya</option>
                                <option value="Porto Editora">Porto Editora</option>
                                <option value="Penguin">Penguin</option>
                            </select>
                            
                            <select class="select select-bordered" onchange="filterTable('livros', this.value, 'preco')">
                                <option value="">Qualquer preço</option>
                                <option value="0-20">€0 - €20</option>
                                <option value="20-40">€20 - €40</option>
                                <option value="40+">€40+</option>
                            </select>
                            
                            <select class="select select-bordered" onchange="filterTable('livros', this.value, 'autor')">
                                <option value="">Todos os autores</option>
                                <option value="J.R.R. Tolkien">J.R.R. Tolkien</option>
                                <option value="George Orwell">George Orwell</option>
                                <option value="Miguel de Cervantes">Miguel de Cervantes</option>
                            </select>
                        </div>

                        <!-- CONTADOR DE RESULTADOS -->
                        <div class="flex justify-between items-center text-sm">
                            <span id="result-count-livros" class="text-base-content/70">
                                Mostrando todos os livros
                            </span>
                            <div class="flex gap-2">
                                <span class="badge badge-ghost" id="active-filters-livros"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABELA LIVROS -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">📚 Gestão de Livros</h2>
                            <button class="btn btn-primary">Adicionar Livro</button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra" id="table-livros">
                                <thead>
                                    <tr>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 0)">
                                            ID <span id="sort-livros-0">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 1)">
                                            ISBN <span id="sort-livros-1">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 2)">
                                            Nome <span id="sort-livros-2">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 3)">
                                            Editora <span id="sort-livros-3">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 4)">
                                            Autores <span id="sort-livros-4">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 5)">
                                            Bibliografia <span id="sort-livros-5">↕</span>
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 6)">
                                            Capa
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 7)">
                                            Preço <span id="sort-livros-7">↕</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-livros">
                                    <!-- Dados serão preenchidos via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- ESTADO VAZIO -->
                        <div id="empty-state-livros" class="text-center py-8 hidden">
                            <div class="text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-lg mb-2">Nenhum livro encontrado</p>
                                <p class="text-sm mb-4">Tente ajustar os filtros ou termos de pesquisa</p>
                                <button class="btn btn-primary" onclick="clearSearch('livros')">
                                    Limpar pesquisa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== TABELA AUTORES ========== -->
                <div id="tab-autores" class="card bg-base-100 shadow-xl hidden">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">✍️ Gestão de Autores</h2>
                        </div>

                        <!-- BARRA DE PESQUISA - AUTORES -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" class="grow" placeholder="Pesquisar autores..." 
                                        onkeyup="searchTable('autores', this.value)"/>
                                </label>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra" id="table-autores">
                                <thead>
                                    <tr>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('autores', 0)">
                                            Nome <span id="sort-autores-0">↕</span>
                                        </th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-autores">
                                    <!-- Dados de exemplo -->
                                    <tr>
                                        <td class="font-semibold">J.R.R. Tolkien</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👨‍💼</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">George Orwell</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👨‍🎓</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Miguel de Cervantes</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👨‍⚔️</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">José Saramago</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👴</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Clarice Lispector</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👩‍💼</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Umberto Eco</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm">👨‍🏫</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========== TABELA EDITORAS ========== -->
                <div id="tab-editoras" class="card bg-base-100 shadow-xl hidden">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">🏢 Gestão de Editoras</h2>
                        </div>

                        <!-- BARRA DE PESQUISA - EDITORAS -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" class="grow" placeholder="Pesquisar editoras..." 
                                        onkeyup="searchTable('editoras', this.value)"/>
                                </label>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra" id="table-editoras">
                                <thead>
                                    <tr>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('editoras', 0)">
                                            Nome <span id="sort-editoras-0">↕</span>
                                        </th>
                                        <th>Logótipo</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-editoras">
                                    <!-- Dados de exemplo -->
                                    <tr>
                                        <td class="font-semibold">Editora Leya</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-base-200 flex items-center justify-center">
                                                    <span class="text-sm font-bold">L</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Porto Editora</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-blue-600">P</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Penguin Random House</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-orange-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-orange-600">🐧</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Bertrand Editora</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-red-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-red-600">B</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Gradiva</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-green-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-green-600">G</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Dom Quixote</td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded bg-purple-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-purple-600">DQ</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  

        </div>
    </div>

    <script>
        // Dados de exemplo para livros
        const livrosData = [
            {
                id: 1,
                isbn: '978-972-004-621-2',
                nome: 'O Senhor dos Anéis',
                editora: 'Editora Leya',
                autores: ['J.R.R. Tolkien'],
                bibliografia: 'Uma jornada épica pela Terra Média onde a Sociedade do Anel tenta destruir o Um Anel para salvar a Terra Média das trevas.',
                preco: '24.99',
                capa: '📖'
            },
            {
                id: 2,
                isbn: '978-019-953-556-9',
                nome: '1984',
                editora: 'Penguin',
                autores: ['George Orwell'],
                bibliografia: 'Um clássico da distopia sobre vigilância total e controle governamental numa sociedade futurista.',
                preco: '16.50',
                capa: '📖'
            },
            {
                id: 3,
                isbn: '978-972-004-732-5',
                nome: 'Dom Quixote',
                editora: 'Porto Editora',
                autores: ['Miguel de Cervantes'],
                bibliografia: 'As aventuras do famoso cavaleiro andante e seu fiel escudeiro Sancho Pança pela Espanha.',
                preco: '19.99',
                capa: '📖'
            },
            {
                id: 4,
                isbn: '978-972-004-823-0',
                nome: 'O Nome da Rosa',
                editora: 'Editora Leya',
                autores: ['Umberto Eco', 'Jorge Luis Borges'],
                bibliografia: 'Mistério medieval num mosteiro beneditino onde uma série de crimes acontece na biblioteca.',
                preco: '22.75',
                capa: '📖'
            }
        ];

        // Variáveis de estado
        let currentSearch = {
            livros: '',
            autores: '',
            editoras: ''
        };

        let currentFilters = {
            livros: {},
            autores: {},
            editoras: {}
        };

        let currentSort = { table: null, column: null, direction: 'asc' };

        // Inicializar a tabela
        document.addEventListener('DOMContentLoaded', function() {
            renderTable('livros', livrosData);
            updateResultCount('livros', livrosData.length);
        });

        // Debounce para pesquisa
        let searchTimeout;
        function debouncedSearch(tableType, searchText) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTable(tableType, searchText);
            }, 300);
        }

        // Função de pesquisa avançada
        function searchTable(tableType, searchText) {
            currentSearch[tableType] = searchText.toLowerCase();
            applyFiltersAndSearch(tableType);
            showSuggestions(tableType, searchText);
        }

        // Aplicar filtros e pesquisa
        function applyFiltersAndSearch(tableType) {
            let filteredData = [...livrosData];
            
            // Aplicar pesquisa
            if (currentSearch[tableType]) {
                filteredData = filteredData.filter(livro => 
                    livro.nome.toLowerCase().includes(currentSearch[tableType]) ||
                    livro.isbn.toLowerCase().includes(currentSearch[tableType]) ||
                    livro.autores.some(autor => autor.toLowerCase().includes(currentSearch[tableType])) ||
                    livro.editora.toLowerCase().includes(currentSearch[tableType]) ||
                    livro.bibliografia.toLowerCase().includes(currentSearch[tableType])
                );
            }
            
            // Aplicar filtros
            Object.keys(currentFilters[tableType]).forEach(filterType => {
                const filterValue = currentFilters[tableType][filterType];
                if (filterValue) {
                    filteredData = filteredData.filter(livro => {
                        switch(filterType) {
                            case 'editora':
                                return livro.editora === filterValue;
                            case 'preco':
                                const price = parseFloat(livro.preco);
                                switch(filterValue) {
                                    case '0-20': return price >= 0 && price <= 20;
                                    case '20-40': return price > 20 && price <= 40;
                                    case '40+': return price > 40;
                                    default: return true;
                                }
                            case 'autor':
                                return livro.autores.includes(filterValue);
                            default:
                                return true;
                        }
                    });
                }
            });
            
            renderTable(tableType, filteredData);
            updateResultCount(tableType, filteredData.length);
            updateActiveFilters(tableType);
        }

        // Renderizar tabela
        function renderTable(tableType, data) {
            const tbody = document.getElementById(`tbody-${tableType}`);
            const emptyState = document.getElementById(`empty-state-${tableType}`);
            
            if (data.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }
            
            emptyState.classList.add('hidden');
            
            tbody.innerHTML = data.map(livro => `
                <tr>
                    <td>${livro.id}</td>
                    <td class="font-mono text-sm">${livro.isbn}</td>
                    <td class="font-semibold">${livro.nome}</td>
                    <td><span class="badge badge-outline">${livro.editora}</span></td>
                    <td>
                        <div class="flex flex-wrap gap-1 max-w-[150px]">
                            ${livro.autores.map(autor => `<span class="badge badge-sm">${autor}</span>`).join('')}
                        </div>
                    </td>
                    <td class="text-sm text-base-content/70 max-w-[200px]">${livro.bibliografia}</td>
                    <td>
                        <div class="avatar">
                            <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center">
                                <span class="text-xs">${livro.capa}</span>
                            </div>
                        </div>
                    </td>
                    <td class="font-semibold">€${livro.preco}</td>
                </tr>
            `).join('');
        }

        // Funções auxiliares (manter as existentes)
        function showTab(tabName) {
            document.getElementById('tab-livros').classList.add('hidden');
            document.getElementById('tab-autores').classList.add('hidden');
            document.getElementById('tab-editoras').classList.add('hidden');
            
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            const tabs = document.querySelectorAll('#mainTabs .tab');
            tabs.forEach(tab => tab.classList.remove('tab-active'));
            event.target.classList.add('tab-active');
        }

        function sortTable(tableType, columnIndex) {
            // ... manter código existente de ordenação ...
        }

        function filterTable(tableType, filterValue, filterType) {
            if (!filterValue) {
                delete currentFilters[tableType][filterType];
            } else {
                currentFilters[tableType][filterType] = filterValue;
            }
            applyFiltersAndSearch(tableType);
        }

        function clearSearch(tableType) {
            currentSearch[tableType] = '';
            currentFilters[tableType] = {};
            document.getElementById(`search-${tableType}`).value = '';
            applyFiltersAndSearch(tableType);
        }

        function updateResultCount(tableType, count) {
            const element = document.getElementById(`result-count-${tableType}`);
            const total = livrosData.length;
            if (count === total) {
                element.textContent = `Mostrando todos os ${count} livros`;
            } else {
                element.textContent = `Mostrando ${count} de ${total} livros`;
            }
        }

        function updateActiveFilters(tableType) {
            const element = document.getElementById(`active-filters-${tableType}`);
            const activeCount = Object.keys(currentFilters[tableType]).length;
            if (activeCount > 0) {
                element.textContent = `${activeCount} filtro(s) ativo(s)`;
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }

        function showSuggestions(tableType, searchText) {
            // Implementar sugestões de pesquisa
            // (pode ser expandido posteriormente)
        }
    </script>
</x-app-layout>