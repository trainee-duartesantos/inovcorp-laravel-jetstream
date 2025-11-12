<style>
    .biblioteca{
        font-size: 3rem;
        line-height: 1.2;
        padding-bottom: 1rem;
    }
    .hover-card {
        background: white;
        border-radius: 3rem;
        border: 2px solid #234fa7;
        box-shadow: 5px 10px 15px -3px rgb(168, 168, 168);
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }
    
    .hover-card:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .card{
        border-radius: 1rem;
        border: 2px solid #234fa7;
        box-shadow: 5px 10px 15px -3px rgb(168, 168, 168);
    }
    .card-title{
        padding: 1rem
    }
    .btn-limpar {
        width: 200px;
        background-color: #1f2937 !important; /* gray-800 */
        color: white !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid #374151 !important; /* gray-700 */
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }
    
    .btn-limpar:hover {
        background-color: #111827 !important; /* gray-900 */
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center w-full gap-8">
            <!-- TÍTULO COM FONTE MAIOR -->
            <h1 class="text-5xl font-bold text-center text-white biblioteca">
                {{ __('Biblioteca') }}
            </h1>
            
            <!-- TABS COMO CARDS INDIVIDUAIS -->
            <div class="flex flex-wrap justify-center gap-6" id="mainTabs">
                <!-- CARD LIVROS -->
                <div class="hover-card w-48" onclick="showTab('livros')">
                    <div class="card-body text-center p-6">
                        <div class="text-4xl mb-3">📚</div>
                        <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Livros</h3>
                    </div>
                </div>

                <!-- CARD AUTORES -->
                <div class="hover-card w-48" onclick="showTab('autores')">
                    <div class="card-body text-center p-6">
                        <div class="text-4xl mb-3">✍️</div>
                        <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Autores</h3>
                    </div>
                </div>

                <!-- CARD EDITORAS -->
                <div class="hover-card w-48" onclick="showTab('editoras')">
                    <div class="card-body text-center p-6">
                        <div class="text-4xl mb-3">🏢</div>
                        <h3 class="card-title justify-center text-lg font-semibold text-gray-800">Editoras</h3>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-white-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ========== SEARCH AVANÇADA - LIVROS ========== -->
            <div id="tab-livros">
                <!-- CARD DE PESQUISA AVANÇADA -->
                <div class="card bg-white shadow-lg mb-6">
                    <div class="card-body">
                        
                        
                        <!-- BARRA DE PESQUISA PRINCIPAL -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-4 items-center">
                            <div class="flex-1 relative" style="padding: 1rem;">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" 
                                        class="grow" 
                                        placeholder="Pesquisar por Nome, ISBN, Autor..." 
                                        id="search-livros"
                                        onkeyup="debouncedSearch('livros', this.value)"/>
                                </label>
                                <!-- SUGESTÕES -->
                                <div id="suggestions-livros" class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden">
                                </div>
                            </div>
                                
                                <!-- BOTÃO CENTRALIZADO VERTICALMENTE -->
                                <div class="flex justify-center sm:justify-start">
                                    <button class="btn-limpar" onclick="clearSearch('livros')">
                                        Limpar
                                    </button>
                                </div>
                        </div>

                        <!-- FILTROS AVANÇADOS -->
                        <div class="flex justify-center mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl px-4">
                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Editora</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200" onchange="filterTable('livros', this.value, 'editora')">
                                        <option value="">Todas as editoras</option>
                                        <option value="Editora Leya">Editora Leya</option>
                                        <option value="Porto Editora">Porto Editora</option>
                                        <option value="Penguin">Penguin</option>
                                    </select>
                                </div>
                                
                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Preço</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200" onchange="filterTable('livros', this.value, 'preco')">
                                        <option value="">Qualquer preço</option>
                                        <option value="0-20">€0 - €20</option>
                                        <option value="20-40">€20 - €40</option>
                                        <option value="40+">€40+</option>
                                    </select>
                                </div>
                                
                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Autor</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200" onchange="filterTable('livros', this.value, 'autor')">
                                        <option value="">Todos os autores</option>
                                        <option value="J.R.R. Tolkien">J.R.R. Tolkien</option>
                                        <option value="George Orwell">George Orwell</option>
                                        <option value="Miguel de Cervantes">Miguel de Cervantes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- CONTADOR DE RESULTADOS -->
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

                <!-- TABELA LIVROS -->
                <div class="card bg-white shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">📚 Gestão de Livros</h2>
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
                                        <th class="cursor-pointer hover:bg-base-200" >
                                            Capa
                                        </th>
                                        <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('livros', 6)">
                                            Preço <span id="sort-livros-6">↕</span>
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
                <div id="tab-autores" class="card bg-white shadow-xl hidden">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">✍️ Gestão de Autores</h2>
                        </div>

                        <!-- BARRA DE PESQUISA - AUTORES -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
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
                <div id="tab-editoras" class="card bg-white shadow-xl hidden">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">🏢 Gestão de Editoras</h2>
                        </div>

                        <!-- BARRA DE PESQUISA - EDITORAS -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
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
    // Dados de exemplo para todas as tabelas
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

    const autoresData = [
        { nome: 'J.R.R. Tolkien', foto: '👨‍💼' },
        { nome: 'George Orwell', foto: '👨‍🎓' },
        { nome: 'Miguel de Cervantes', foto: '👨‍⚔️' },
        { nome: 'José Saramago', foto: '👴' },
        { nome: 'Clarice Lispector', foto: '👩‍💼' },
        { nome: 'Umberto Eco', foto: '👨‍🏫' }
    ];

    const editorasData = [
        { nome: 'Editora Leya', logo: 'L', cor: 'base' },
        { nome: 'Porto Editora', logo: 'P', cor: 'blue' },
        { nome: 'Penguin Random House', logo: '🐧', cor: 'orange' },
        { nome: 'Bertrand Editora', logo: 'B', cor: 'red' },
        { nome: 'Gradiva', logo: 'G', cor: 'green' },
        { nome: 'Dom Quixote', logo: 'DQ', cor: 'purple' }
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

    // Inicializar todas as tabelas
    document.addEventListener('DOMContentLoaded', function() {
        renderTable('livros', livrosData);
        renderTable('autores', autoresData);
        renderTable('editoras', editorasData);
        updateResultCount('livros', livrosData.length);
        updateResultCount('autores', autoresData.length);
        updateResultCount('editoras', editorasData.length);
    });

    // Debounce para pesquisa
    let searchTimeout;
    function debouncedSearch(tableType, searchText) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchTable(tableType, searchText);
        }, 300);
    }

    // Função de pesquisa
    function searchTable(tableType, searchText) {
        currentSearch[tableType] = searchText.toLowerCase();
        applyFiltersAndSearch(tableType);
    }

    // Aplicar filtros e pesquisa
    function applyFiltersAndSearch(tableType) {
        let filteredData;
        
        switch(tableType) {
            case 'livros':
                filteredData = [...livrosData];
                if (currentSearch[tableType]) {
                    filteredData = filteredData.filter(livro => 
                        livro.nome.toLowerCase().includes(currentSearch[tableType]) ||
                        livro.isbn.toLowerCase().includes(currentSearch[tableType]) ||
                        livro.autores.some(autor => autor.toLowerCase().includes(currentSearch[tableType])) ||
                        livro.editora.toLowerCase().includes(currentSearch[tableType]) ||
                        livro.bibliografia.toLowerCase().includes(currentSearch[tableType])
                    );
                }
                break;
                
            case 'autores':
                filteredData = [...autoresData];
                if (currentSearch[tableType]) {
                    filteredData = filteredData.filter(autor => 
                        autor.nome.toLowerCase().includes(currentSearch[tableType])
                    );
                }
                break;
                
            case 'editoras':
                filteredData = [...editorasData];
                if (currentSearch[tableType]) {
                    filteredData = filteredData.filter(editora => 
                        editora.nome.toLowerCase().includes(currentSearch[tableType])
                    );
                }
                break;
        }
        
        // Aplicar filtros específicos para livros
        if (tableType === 'livros') {
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
        }
        
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
            if (emptyState) emptyState.classList.remove('hidden');
            return;
        }
        
        if (emptyState) emptyState.classList.add('hidden');
        
        switch(tableType) {
            case 'livros':
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
                break;
                
            case 'autores':
                tbody.innerHTML = data.map(autor => `
                    <tr>
                        <td class="font-semibold">${autor.nome}</td>
                        <td>
                            <div class="avatar">
                                <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                                    <span class="text-sm">${autor.foto}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                `).join('');
                break;
                
            case 'editoras':
                tbody.innerHTML = data.map(editora => {
                    const colorClass = editora.cor === 'base' ? 'bg-base-200' : `bg-${editora.cor}-100`;
                    const textClass = editora.cor === 'base' ? '' : `text-${editora.cor}-600`;
                    return `
                    <tr>
                        <td class="font-semibold">${editora.nome}</td>
                        <td>
                            <div class="avatar">
                                <div class="w-12 h-12 rounded ${colorClass} flex items-center justify-center">
                                    <span class="text-sm font-bold ${textClass}">${editora.logo}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                `}).join('');
                break;
        }
    }

    // Função para mostrar/ocultar tabs
    function showTab(tabName) {
        document.getElementById('tab-livros').classList.add('hidden');
        document.getElementById('tab-autores').classList.add('hidden');
        document.getElementById('tab-editoras').classList.add('hidden');
        
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        const tabs = document.querySelectorAll('#mainTabs .tab');
        tabs.forEach(tab => tab.classList.remove('tab-active'));
        event.target.classList.add('tab-active');
    }

    // Função de ordenação
    function sortTable(tableType, columnIndex) {
        let data;
        switch(tableType) {
            case 'livros': data = [...livrosData]; break;
            case 'autores': data = [...autoresData]; break;
            case 'editoras': data = [...editorasData]; break;
        }
        
        // Verificar se estamos a ordenar a mesma coluna para alternar direção
        if (currentSort.table === tableType && currentSort.column === columnIndex) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = { table: tableType, column: columnIndex, direction: 'asc' };
        }
        
        // Atualizar indicadores visuais
        updateSortIndicators(tableType, columnIndex);
        
        data.sort((a, b) => {
            let aValue, bValue;
            
            switch(tableType) {
                case 'livros':
                    aValue = Object.values(a)[columnIndex];
                    bValue = Object.values(b)[columnIndex];
                    break;
                case 'autores':
                case 'editoras':
                    aValue = Object.values(a)[columnIndex];
                    bValue = Object.values(b)[columnIndex];
                    break;
            }
            
            // Verificar se é número
            if (!isNaN(aValue) && !isNaN(bValue)) {
                return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
            }
            
            // Ordenação de texto
            return currentSort.direction === 'asc' 
                ? String(aValue).localeCompare(String(bValue))
                : String(bValue).localeCompare(String(aValue));
        });
        
        renderTable(tableType, data);
    }

    // Atualizar indicadores visuais de ordenação
    function updateSortIndicators(tableType, columnIndex) {
        // Resetar todos os indicadores desta tabela
        const maxColumns = tableType === 'livros' ? 8 : 2;
        for (let i = 0; i < maxColumns; i++) {
            const indicator = document.getElementById(`sort-${tableType}-${i}`);
            if (indicator) indicator.textContent = '↕';
        }
        
        // Atualizar indicador da coluna atual
        const currentIndicator = document.getElementById(`sort-${tableType}-${columnIndex}`);
        if (currentIndicator) {
            currentIndicator.textContent = currentSort.direction === 'asc' ? '↑' : '↓';
        }
    }

    // Função de filtragem (apenas para livros)
    function filterTable(tableType, filterValue, filterType) {
        if (!filterValue) {
            delete currentFilters[tableType][filterType];
        } else {
            currentFilters[tableType][filterType] = filterValue;
        }
        applyFiltersAndSearch(tableType);
    }

    // Limpar pesquisa
    function clearSearch(tableType) {
        currentSearch[tableType] = '';
        currentFilters[tableType] = {};
        const searchInput = document.getElementById(`search-${tableType}`);
        if (searchInput) searchInput.value = '';
        applyFiltersAndSearch(tableType);
    }

    // Atualizar contador de resultados
    function updateResultCount(tableType, count) {
        const element = document.getElementById(`result-count-${tableType}`);
        if (!element) return;
        
        let total;
        switch(tableType) {
            case 'livros': total = livrosData.length; break;
            case 'autores': total = autoresData.length; break;
            case 'editoras': total = editorasData.length; break;
        }
        
        if (count === total) {
            element.textContent = `Mostrando todos os ${count} ${tableType}`;
        } else {
            element.textContent = `Mostrando ${count} de ${total} ${tableType}`;
        }
    }

    // Atualizar badges de filtros ativos
    function updateActiveFilters(tableType) {
        const element = document.getElementById(`active-filters-${tableType}`);
        if (!element) return;
        
        const activeCount = Object.keys(currentFilters[tableType]).length;
        if (activeCount > 0) {
            element.textContent = `${activeCount} filtro(s) ativo(s)`;
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    }
</script>
</x-app-layout>