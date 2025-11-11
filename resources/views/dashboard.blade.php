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

            <!-- CONTEÚDO DAS TABS -->
            <div class="space-y-8">
                
                <!-- ========== TABELA LIVROS ========== -->
                <div id="tab-livros" class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="card-title">📚 Gestão de Livros</h2>
                        </div>

                        <!-- BARRA DE PESQUISA E FILTROS - LIVROS -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <label class="input input-bordered flex items-center gap-2">
                                    <input type="text" class="grow" placeholder="Pesquisar livros..." 
                                        onkeyup="searchTable('livros', this.value)"/>
                                </label>
                            </div>
                            <select class="select select-bordered w-full sm:w-auto" onchange="filterTable('livros', this.value, 'editora')">
                                <option value="">Todas as editoras</option>
                                <option value="Editora Leya">Editora Leya</option>
                                <option value="Porto Editora">Porto Editora</option>
                                <option value="Penguin">Penguin</option>
                            </select>
                            <select class="select select-bordered w-full sm:w-auto" onchange="filterTable('livros', this.value, 'preco')">
                                <option value="">Qualquer preço</option>
                                <option value="0-20">€0 - €20</option>
                                <option value="20-40">€20 - €40</option>
                                <option value="40+">€40+</option>
                            </select>
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
                                    <!-- Dados de exemplo -->
                                    <tr>
                                        <td>1</td>
                                        <td class="font-mono text-sm">978-972-004-621-2</td>
                                        <td class="font-semibold">O Senhor dos Anéis</td>
                                        <td>
                                            <span class="badge badge-outline">Editora Leya</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                                <span class="badge badge-sm">J.R.R. Tolkien</span>
                                            </div>
                                        </td>
                                        <td class="text-sm text-base-content/70 max-w-[200px]">
                                            Uma jornada épica pela Terra Média onde a Sociedade do Anel tenta destruir o Um Anel para salvar a Terra Média das trevas.
                                        </td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center">
                                                    <span class="text-xs text-base-content/60">📖</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold">€24.99</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="font-mono text-sm">978-019-953-556-9</td>
                                        <td class="font-semibold">1984</td>
                                        <td>
                                            <span class="badge badge-outline">Penguin</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                                <span class="badge badge-sm">George Orwell</span>
                                            </div>
                                        </td>
                                        <td class="text-sm text-base-content/70 max-w-[200px]">
                                            Um clássico da distopia sobre vigilância total e controle governamental numa sociedade futurista.
                                        </td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center">
                                                    <span class="text-xs text-base-content/60">📖</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold">€16.50</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="font-mono text-sm">978-972-004-732-5</td>
                                        <td class="font-semibold">Dom Quixote</td>
                                        <td>
                                            <span class="badge badge-outline">Porto Editora</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                                <span class="badge badge-sm">Miguel de Cervantes</span>
                                            </div>
                                        </td>
                                        <td class="text-sm text-base-content/70 max-w-[200px]">
                                            As aventuras do famoso cavaleiro andante e seu fiel escudeiro Sancho Pança pela Espanha.
                                        </td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center">
                                                    <span class="text-xs text-base-content/60">📖</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold">€19.99</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td class="font-mono text-sm">978-972-004-823-0</td>
                                        <td class="font-semibold">O Nome da Rosa</td>
                                        <td>
                                            <span class="badge badge-outline">Editora Leya</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                                <span class="badge badge-sm">Umberto Eco</span>
                                                <span class="badge badge-sm">Jorge Luis Borges</span>
                                            </div>
                                        </td>
                                        <td class="text-sm text-base-content/70 max-w-[200px]">
                                            Mistério medieval num mosteiro beneditino onde uma série de crimes acontece na biblioteca.
                                        </td>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center">
                                                    <span class="text-xs text-base-content/60">📖</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold">€22.75</td>
                                    </tr>
                                </tbody>
                            </table>
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
    </div>

    <script>
        // Variáveis para controlar ordenação
        let currentSort = { table: null, column: null, direction: 'asc' };

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

        // Função de PESQUISA
        function searchTable(tableType, searchText) {
            const tbody = document.getElementById(`tbody-${tableType}`);
            const rows = tbody.getElementsByTagName('tr');
            
            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(searchText.toLowerCase())) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            }
        }

        // Função de ORDENAÇÃO (agora com reversão)
        function sortTable(tableType, columnIndex) {
            const tbody = document.getElementById(`tbody-${tableType}`);
            const rows = Array.from(tbody.getElementsByTagName('tr'));
            
            // Verificar se estamos a ordenar a mesma coluna para alternar direção
            if (currentSort.table === tableType && currentSort.column === columnIndex) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = { table: tableType, column: columnIndex, direction: 'asc' };
            }
            
            // Atualizar indicadores visuais
            updateSortIndicators(tableType, columnIndex);
            
            rows.sort((a, b) => {
                const aText = a.getElementsByTagName('td')[columnIndex].textContent;
                const bText = b.getElementsByTagName('td')[columnIndex].textContent;
                
                let result;
                // Verificar se é número
                if (!isNaN(aText) && !isNaN(bText)) {
                    result = Number(aText) - Number(bText);
                } else {
                    result = aText.localeCompare(bText);
                }
                
                // Aplicar direção
                return currentSort.direction === 'asc' ? result : -result;
            });
            
            // Limpar e adicionar linhas ordenadas
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        // Atualizar indicadores visuais de ordenação
        function updateSortIndicators(tableType, columnIndex) {
            // Resetar todos os indicadores desta tabela
            for (let i = 0; i < 4; i++) {
                const indicator = document.getElementById(`sort-${tableType}-${i}`);
                if (indicator) indicator.textContent = '↕';
            }
            
            // Atualizar indicador da coluna atual
            const currentIndicator = document.getElementById(`sort-${tableType}-${columnIndex}`);
            if (currentIndicator) {
                currentIndicator.textContent = currentSort.direction === 'asc' ? '↑' : '↓';
            }
        }

        // Função de FILTRAGEM
            function filterTable(tableType, filterValue, columnName) {
                const tbody = document.getElementById(`tbody-${tableType}`);
                const rows = tbody.getElementsByTagName('tr');
                const columnIndex = getColumnIndex(tableType, columnName);
                
                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    const cellValue = cells[columnIndex].textContent;
                    
                    let shouldShow = true;
                    
                    if (filterValue) {
                        if (columnName === 'preco') {
                            // Filtro especial para preços
                            const price = parseFloat(cellValue.replace('€', '').replace(',', '.'));
                            switch(filterValue) {
                                case '0-20': shouldShow = price >= 0 && price <= 20; break;
                                case '20-40': shouldShow = price > 20 && price <= 40; break;
                                case '40+': shouldShow = price > 40; break;
                            }
                        } else {
                            // Filtro normal para texto
                            shouldShow = cellValue === filterValue;
                        }
                    }
                    
                    row.style.display = shouldShow ? '' : 'none';
                }
            }

        // Helper para obter índice da coluna
            function getColumnIndex(tableType, columnName) {
                const maps = {
                    'livros': { 
                        'editora': 3,
                        'preco': 7
                    },
                    'autores': { 
                        // Não precisamos de filtros específicos para autores
                    },
                    'editoras': { 
                        // Não precisamos de filtros específicos para editoras
                    }
                };
                return maps[tableType][columnName] || 0;
            }
    </script>
</x-app-layout>