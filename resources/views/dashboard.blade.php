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
                
                <!-- TABELA LIVROS (VISÍVEL POR PADRÃO) -->
                <div id="tab-livros" class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">📚 Gestão de Livros</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Ano</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-8">
                                            <div class="text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                <p>Nenhum livro encontrado</p>
                                                <button class="btn btn-primary btn-sm mt-2">Adicionar Livro</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABELA AUTORES (OCULTA POR PADRÃO) -->
                <div id="tab-autores" class="card bg-base-100 shadow-xl hidden">
                    <div class="card-body">
                        <h2 class="card-title">✍️ Gestão de Autores</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Nacionalidade</th>
                                        <th>Livros Publicados</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-8">
                                            <div class="text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <p>Nenhum autor encontrado</p>
                                                <button class="btn btn-primary btn-sm mt-2">Adicionar Autor</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABELA EDITORAS (OCULTA POR PADRÃO) -->
                <div id="tab-editoras" class="card bg-base-100 shadow-xl hidden">
                    <div class="card-body">
                        <h2 class="card-title">🏢 Gestão de Editoras</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Localização</th>
                                        <th>Ano Fundação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-8">
                                            <div class="text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <p>Nenhuma editora encontrada</p>
                                                <button class="btn btn-primary btn-sm mt-2">Adicionar Editora</button>
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
        function showTab(tabName) {
            // Esconder todas as tabs de conteúdo
            document.getElementById('tab-livros').classList.add('hidden');
            document.getElementById('tab-autores').classList.add('hidden');
            document.getElementById('tab-editoras').classList.add('hidden');
            
            // Mostrar apenas a tab selecionada
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            // Atualizar estado visual das tabs
            const tabs = document.querySelectorAll('#mainTabs .tab');
            tabs.forEach(tab => {
                tab.classList.remove('tab-active');
            });
            event.target.classList.add('tab-active');
        }
    </script>
</x-app-layout>