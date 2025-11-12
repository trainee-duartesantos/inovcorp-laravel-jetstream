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
        background-color: #1f2937 !important;
        color: white !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid #374151 !important;
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
        background-color: #111827 !important;
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    }

    .book-cover {
        width: 48px;
        height: 64px;
        object-fit: cover;
        border-radius: 4px;
        transition: transform 0.2s ease-in-out;
    }

    .book-cover:hover {
        transform: scale(1.1);
    }
    .author-photo {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: transform 0.2s ease-in-out;
    }

    .author-photo:hover {
        transform: scale(1.05);
    }

    .author-photo-container {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
    @media (max-width: 768px) {
        .author-photo,
        .author-photo-container {
            width: 120px;
            height: 120px;
        }
    }
    .publisher-logo {
        width: 200px;
        height: 200px;
        object-fit: contain;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        background: white;
        padding: 8px;
        transition: transform 0.2s ease-in-out;
    }

    .publisher-logo:hover {
        transform: scale(1.05);
    }

    .publisher-logo-container {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        font-weight: bold;
        color: #374151;
    }
    @media (max-width: 768px) {
    .publisher-logo,
    .publisher-logo-container {
        width: 120px;
        height: 120px;
    }
}
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center w-full gap-8">
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
    </x-slot>

    <div class="py-6 bg-white-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ========== TAB LIVROS ========== -->
            <div id="tab-livros">
                <div class="card bg-white shadow-lg mb-6">
                    <div class="card-body">
                        <div class="flex flex-col sm:flex-row gap-4 mb-4 items-center">
                            <div class="flex-1 relative" style="padding: 1rem;">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" 
                                        class="grow" 
                                        placeholder="Pesquisar por Nome, ISBN, Autor..." 
                                        id="search-livros"
                                        onkeyup="debouncedSearch('livros', this.value)"/>
                                </label>
                                <div id="suggestions-livros" class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden">
                                </div>
                            </div>
                            <div class="flex justify-center sm:justify-start">
                                <button class="btn-limpar" onclick="clearSearch('livros')">
                                    Limpar
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-center mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl px-4">
                                <div class="flex flex-col">
                                    <label class="text-sm font-semibold text-gray-700 mb-2">Editora</label>
                                    <select class="select select-bordered w-full bg-white shadow-lg rounded-xl border-gray-200" onchange="filterTable('livros', this.value, 'editora')">
                                        <option value="">Todas as editoras</option>
                                        <option value="Editora Leya">Editora Leya</option>
                                        <option value="Porto Editora">Porto Editora</option>
                                        <option value="Penguin Random House">Penguin Random House</option>
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

                <div class="card bg-white shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">📚 Gestão de Livros</h2>
                        </div>
                        
                        <div class="overflow-x-auto">
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
                                    <!-- Dados preenchidos via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <div id="empty-state-livros" class="text-center py-8 hidden">
                            <div class="text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-lg mb-2">Nenhum livro encontrado</p>
                                <p class="text-sm mb-4">Tente ajustar os filtros ou termos de pesquisa</p>
                                <button class="btn btn-primary" onclick="clearSearch('livros')">Limpar pesquisa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== TAB AUTORES ========== -->
            <div id="tab-autores" class="card bg-white shadow-xl hidden">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">✍️ Gestão de Autores</h2>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
                        <div class="flex-1">
                            <label class="input input-bordered flex items-center gap-2">
                                <input type="text" class="grow" placeholder="Pesquisar autores..." onkeyup="searchTable('autores', this.value)"/>
                            </label>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="table-autores">
                            <thead>
                                <tr>
                                    <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('autores', 0)">Nome <span id="sort-autores-0">↕</span></th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-autores">
                                <!-- Dados preenchidos via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========== TAB EDITORAS ========== -->
            <div id="tab-editoras" class="card bg-white shadow-xl hidden">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">🏢 Gestão de Editoras</h2>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-6" style="padding: 1rem;">
                        <div class="flex-1">
                            <label class="input input-bordered flex items-center gap-2">
                                <input type="text" class="grow" placeholder="Pesquisar editoras..." onkeyup="searchTable('editoras', this.value)"/>
                            </label>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra" id="table-editoras">
                            <thead>
                                <tr>
                                    <th class="cursor-pointer hover:bg-base-200" onclick="sortTable('editoras', 0)">Nome <span id="sort-editoras-0">↕</span></th>
                                    <th>Logótipo</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-editoras">
                                <!-- Dados preenchidos via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <script>
    // Dados REAIS com imagens
    const livrosData = [
        {
            id: 1,
            isbn: '978-972-004-621-2',
            nome: 'O Senhor dos Anéis',
            editora: 'Editora Leya',
            autores: ['J.R.R. Tolkien'],
            bibliografia: 'Uma jornada épica pela Terra Média onde a Sociedade do Anel tenta destruir o Um Anel para salvar a Terra Média das trevas.',
            preco: '24.99',
            capa_url: 'images/livros/senhor-dos-aneis.jpg',
            capa: '📖'
        },
        {
            id: 2,
            isbn: '978-972-0-07061-0',
            nome: '1984',
            editora: 'Porto Editora',
            autores: ['George Orwell'],
            bibliografia: 'Um clássico da distopia sobre vigilância total e controle governamental numa sociedade futurista.',
            preco: '16.50',
            capa_url: 'images/livros/1984.jpg',
            capa: '📖'
        },
        {
            id: 3,
            isbn: '978-972-004-732-5',
            nome: 'Dom Quixote de La Mancha',
            editora: 'Porto Editora',
            autores: ['Miguel de Cervantes'],
            bibliografia: 'As aventuras do famoso cavaleiro andante e seu fiel escudeiro Sancho Pança pela Espanha.',
            preco: '19.99',
            capa_url: 'images/livros/don-quixote.jpg',
            capa: '📖'
        },
        {
            id: 4,
            isbn: '978-972-004-823-0',
            nome: 'O Nome da Rosa',
            editora: 'Editora Leya',
            autores: ['Umberto Eco'],
            bibliografia: 'Mistério medieval num mosteiro beneditino onde uma série de crimes acontece na biblioteca.',
            preco: '22.75',
            capa_url: 'images/livros/nome-da-rosa.jpg',
            capa: '📖'
        }
    ];

    const autoresData = [
        { nome: 'J.R.R. Tolkien', foto_url: 'images/autores/jrr-tolkien.jpg' },
        { nome: 'George Orwell', foto_url: 'images/autores/george-orwell.jpg' },
        { nome: 'Miguel de Cervantes', foto_url: 'images/autores/miguel-de-cervantes.jpg' },
        { nome: 'José Saramago', foto_url: 'images/autores/jose-saramago.jpg' },
        { nome: 'Umberto Eco', foto_url: 'images/autores/umberto-eco.jpg' }
    ];

    const editorasData = [
        { nome: 'Porto Editora', logo_url: 'images/editoras/porto-editora.jpg' },
        { nome: 'Penguin Random House', logo_url: 'images/editoras/penguin.jpg' },
        { nome: 'Editora Leya', logo_url: 'images/editoras/leya.jpg' },
        { nome: 'Bertrand Editora', logo_url: 'images/editoras/bertrand.jpg' }
    ];

    // Variáveis de estado
    let currentSearch = { livros: '', autores: '', editoras: '' };
    let currentFilters = { livros: {}, autores: {}, editoras: {} };
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

    // Renderizar tabela COM IMAGENS REAIS
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
                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center overflow-hidden">
                                    ${livro.capa_url ? 
                                        `<img src="{{ asset('${livro.capa_url}') }}" 
                                              alt="Capa de ${livro.nome}" 
                                              class="book-cover"
                                              onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                         <div class="w-full h-full flex items-center justify-center hidden">
                                             <span class="text-xs">${livro.capa}</span>
                                         </div>` 
                                        : `<span class="text-xs">${livro.capa}</span>`
                                    }
                                </div>
                            </div>
                        </td>
                        <td class="font-semibold">€${livro.preco}</td>
                    </tr>
                `).join('');
                break;
                
            case 'autores':
                tbody.innerHTML = data.map(autor => {
                    const hasPhoto = autor.foto_url && autor.foto_url !== 'null' && autor.foto_url !== '';
                    
                    return `
                    <tr class="hover:bg-base-100">
                        <td class="px-4 py-3 font-semibold text-lg">${autor.nome}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center">
                                ${hasPhoto ? 
                                    `<img src="${autor.foto_url}" 
                                        alt="Foto de ${autor.nome}" 
                                        class="author-photo"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="author-photo-container hidden">
                                        <span class="text-4xl">👨‍💼</span>
                                    </div>` 
                                    : `<div class="author-photo-container">
                                        <span class="text-4xl">👨‍💼</span>
                                    </div>`
                                }
                            </div>
                        </td>
                    </tr>
                `}).join('');
            break;
                
            case 'editoras':
                tbody.innerHTML = data.map(editora => {
                    const hasLogo = editora.logo_url && editora.logo_url !== 'null' && editora.logo_url !== '';
                    
                    return `
                    <tr class="hover:bg-base-100">
                        <td class="px-4 py-3 font-semibold text-lg">${editora.nome}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center">
                                ${hasLogo ? 
                                    `<img src="${editora.logo_url}" 
                                        alt="Logótipo de ${editora.nome}" 
                                        class="publisher-logo"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="publisher-logo-container hidden">
                                        <span class="text-2xl font-bold">${editora.nome.substring(0, 2)}</span>
                                    </div>` 
                                    : `<div class="publisher-logo-container">
                                        <span class="text-2xl font-bold">${editora.nome.substring(0, 2)}</span>
                                    </div>`
                                }
                            </div>
                        </td>
                    </tr>
                `}).join('');
            break;
        }
    }

    // Função para mostrar/ocultar tabs
    function showTab(tabName) {
        // Esconder todas as tabs
        document.getElementById('tab-livros').classList.add('hidden');
        document.getElementById('tab-autores').classList.add('hidden');
        document.getElementById('tab-editoras').classList.add('hidden');
        
        // Mostrar a tab selecionada
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Atualizar estado visual dos cards
        const cards = document.querySelectorAll('#mainTabs .hover-card');
        cards.forEach(card => {
            card.classList.remove('bg-primary', 'text-primary-content');
            card.classList.add('bg-white');
        });
        
        // Destacar o card clicado
        event.currentTarget.classList.remove('bg-white');
        event.currentTarget.classList.add('bg-primary', 'text-primary-content');
    }

    // Função de ordenação
    function sortTable(tableType, columnIndex) {
        let data;
        switch(tableType) {
            case 'livros': data = [...livrosData]; break;
            case 'autores': data = [...autoresData]; break;
            case 'editoras': data = [...editorasData]; break;
        }
        
        if (currentSort.table === tableType && currentSort.column === columnIndex) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = { table: tableType, column: columnIndex, direction: 'asc' };
        }
        
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
            
            if (!isNaN(aValue) && !isNaN(bValue)) {
                return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
            }
            
            return currentSort.direction === 'asc' 
                ? String(aValue).localeCompare(String(bValue))
                : String(bValue).localeCompare(String(aValue));
        });
        
        renderTable(tableType, data);
    }

    // Atualizar indicadores visuais de ordenação
    function updateSortIndicators(tableType, columnIndex) {
        const maxColumns = tableType === 'livros' ? 8 : 2;
        for (let i = 0; i < maxColumns; i++) {
            const indicator = document.getElementById(`sort-${tableType}-${i}`);
            if (indicator) indicator.textContent = '↕';
        }
        
        const currentIndicator = document.getElementById(`sort-${tableType}-${columnIndex}`);
        if (currentIndicator) {
            currentIndicator.textContent = currentSort.direction === 'asc' ? '↑' : '↓';
        }
    }

    // Função de filtragem
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
        
        // Resetar selects
        const selects = document.querySelectorAll(`#tab-${tableType} select`);
        selects.forEach(select => select.value = '');
        
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