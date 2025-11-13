// Dados REAIS com imagens
const livrosData = [
    {
        id: 1,
        isbn: "978-972-004-621-2",
        nome: "O Senhor dos Anéis",
        editora: "Editora Leya",
        autores: ["J.R.R. Tolkien"],
        bibliografia:
            "Uma jornada épica pela Terra Média onde a Sociedade do Anel tenta destruir o Um Anel para salvar a Terra Média das trevas.",
        preco: "24.99",
        capa_url: "images/livros/senhor-dos-aneis.jpg",
        capa: "📖",
    },
    {
        id: 2,
        isbn: "978-972-0-07061-0",
        nome: "1984",
        editora: "Porto Editora",
        autores: ["George Orwell"],
        bibliografia:
            "Um clássico da distopia sobre vigilância total e controle governamental numa sociedade futurista.",
        preco: "16.50",
        capa_url: "images/livros/1984.jpg",
        capa: "📖",
    },
    {
        id: 3,
        isbn: "978-972-004-732-5",
        nome: "Dom Quixote de La Mancha",
        editora: "Porto Editora",
        autores: ["Miguel de Cervantes"],
        bibliografia:
            "As aventuras do famoso cavaleiro andante e seu fiel escudeiro Sancho Pança pela Espanha.",
        preco: "19.99",
        capa_url: "images/livros/don-quixote.jpg",
        capa: "📖",
    },
    {
        id: 4,
        isbn: "978-972-004-823-0",
        nome: "O Nome da Rosa",
        editora: "Editora Leya",
        autores: ["Umberto Eco"],
        bibliografia:
            "Mistério medieval num mosteiro beneditino onde uma série de crimes acontece na biblioteca.",
        preco: "22.75",
        capa_url: "images/livros/nome-da-rosa.jpg",
        capa: "📖",
    },
];

const autoresData = [
    { nome: "J.R.R. Tolkien", foto_url: "images/autores/jrr-tolkien.jpg" },
    { nome: "George Orwell", foto_url: "images/autores/george-orwell.jpg" },
    {
        nome: "Miguel de Cervantes",
        foto_url: "images/autores/miguel-de-cervantes.jpg",
    },
    { nome: "José Saramago", foto_url: "images/autores/jose-saramago.jpg" },
    { nome: "Umberto Eco", foto_url: "images/autores/umberto-eco.jpg" },
];

const editorasData = [
    { nome: "Porto Editora", logo_url: "images/editoras/porto-editora.jpg" },
    { nome: "Penguin Random House", logo_url: "images/editoras/penguin.jpg" },
    { nome: "Editora Leya", logo_url: "images/editoras/leya.jpg" },
    { nome: "Bertrand Editora", logo_url: "images/editoras/bertrand.jpg" },
];

// Variáveis de estado
let currentSearch = { livros: "", autores: "", editoras: "" };
let currentFilters = { livros: {}, autores: {}, editoras: {} };
let currentSort = { table: null, column: null, direction: "asc" };

// Inicializar ambas as versões
document.addEventListener("DOMContentLoaded", function () {
    renderTable("livros", livrosData);
    renderMobileLivros(livrosData);
    renderTable("autores", autoresData);
    renderTable("editoras", editorasData);
    updateResultCount("livros", livrosData.length);
    updateResultCount("autores", autoresData.length);
    updateResultCount("editoras", editorasData.length);
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

    switch (tableType) {
        case "livros":
            filteredData = [...livrosData];
            if (currentSearch[tableType]) {
                filteredData = filteredData.filter(
                    (livro) =>
                        livro.nome
                            .toLowerCase()
                            .includes(currentSearch[tableType]) ||
                        livro.isbn
                            .toLowerCase()
                            .includes(currentSearch[tableType]) ||
                        livro.autores.some((autor) =>
                            autor
                                .toLowerCase()
                                .includes(currentSearch[tableType])
                        ) ||
                        livro.editora
                            .toLowerCase()
                            .includes(currentSearch[tableType]) ||
                        livro.bibliografia
                            .toLowerCase()
                            .includes(currentSearch[tableType])
                );
            }
            break;

        case "autores":
            filteredData = [...autoresData];
            if (currentSearch[tableType]) {
                filteredData = filteredData.filter((autor) =>
                    autor.nome.toLowerCase().includes(currentSearch[tableType])
                );
            }
            break;

        case "editoras":
            filteredData = [...editorasData];
            if (currentSearch[tableType]) {
                filteredData = filteredData.filter((editora) =>
                    editora.nome
                        .toLowerCase()
                        .includes(currentSearch[tableType])
                );
            }
            break;
    }

    // Aplicar filtros específicos para livros
    if (tableType === "livros") {
        Object.keys(currentFilters[tableType]).forEach((filterType) => {
            const filterValue = currentFilters[tableType][filterType];
            if (filterValue) {
                filteredData = filteredData.filter((livro) => {
                    switch (filterType) {
                        case "editora":
                            return livro.editora === filterValue;
                        case "preco":
                            const price = parseFloat(livro.preco);
                            switch (filterValue) {
                                case "0-20":
                                    return price >= 0 && price <= 20;
                                case "20-40":
                                    return price > 20 && price <= 40;
                                case "40+":
                                    return price > 40;
                                default:
                                    return true;
                            }
                        case "autor":
                            return livro.autores.includes(filterValue);
                        default:
                            return true;
                    }
                });
            }
        });
    }

    // Renderizar ambas as versões (desktop e mobile)
    renderTable(tableType, filteredData);
    if (tableType === "livros") {
        renderMobileLivros(filteredData);
    }
    updateResultCount(tableType, filteredData.length);
    updateActiveFilters(tableType);
}

// Renderizar tabela desktop
function renderTable(tableType, data) {
    const tbody = document.getElementById(`tbody-${tableType}`);
    const emptyState = document.getElementById(`empty-state-${tableType}`);

    if (data.length === 0) {
        tbody.innerHTML = "";
        if (emptyState) emptyState.classList.remove("hidden");
        return;
    }

    if (emptyState) emptyState.classList.add("hidden");

    switch (tableType) {
        case "livros":
            tbody.innerHTML = data
                .map((livro) => {
                    const hasImage =
                        livro.capa_url &&
                        livro.capa_url !== "null" &&
                        livro.capa_url !== "";

                    return `
                    <tr>
                        <td>${livro.id}</td>
                        <td class="font-mono text-sm">${livro.isbn}</td>
                        <td class="font-semibold">${livro.nome}</td>
                        <td><span class="badge badge-outline">${
                            livro.editora
                        }</span></td>
                        <td>
                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                ${livro.autores
                                    .map(
                                        (autor) =>
                                            `<span class="badge badge-sm">${autor}</span>`
                                    )
                                    .join("")}
                            </div>
                        </td>
                        <td class="text-sm text-base-content/70 max-w-[200px]">${
                            livro.bibliografia
                        }</td>
                        <td>
                            <div class="avatar">
                                <div class="w-12 h-16 rounded bg-base-200 flex items-center justify-center overflow-hidden">
                                    ${
                                        hasImage
                                            ? `<img src="${livro.capa_url}" 
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
                `;
                })
                .join("");
            break;

        case "autores":
            tbody.innerHTML = data
                .map((autor) => {
                    const hasPhoto =
                        autor.foto_url &&
                        autor.foto_url !== "null" &&
                        autor.foto_url !== "";

                    return `
                    <tr class="hover:bg-base-100">
                        <td class="px-4 py-3 font-semibold text-lg">${
                            autor.nome
                        }</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center items-center">
                                ${
                                    hasPhoto
                                        ? `<img src="${autor.foto_url}" 
                                            alt="Foto de ${autor.nome}" 
                                            class="author-photo"
                                            onerror="this.src='images/placeholders/placeholder-author.jpg'">`
                                        : `<div class="author-photo-container">
                                            <span class="text-4xl">👨‍💼</span>
                                        </div>`
                                }
                            </div>
                        </td>
                    </tr>
                `;
                })
                .join("");
            break;

        case "editoras":
            tbody.innerHTML = data
                .map((editora) => {
                    const hasLogo =
                        editora.logo_url &&
                        editora.logo_url !== "null" &&
                        editora.logo_url !== "";

                    return `
            <tr class="hover:bg-base-100">
                <td class="px-4 py-3 font-semibold text-lg">${editora.nome}</td>
                <td class="px-4 py-3">
                    <div class="flex justify-center items-center">
                        ${
                            hasLogo
                                ? `<img src="${editora.logo_url}" 
                                    alt="Logótipo de ${editora.nome}" 
                                    class="publisher-logo"
                                    onerror="this.src='images/placeholders/placeholder-publisher.svg'">`
                                : `<div class="publisher-logo-container">
                                    <span class="text-2xl font-bold">${editora.nome.substring(
                                        0,
                                        2
                                    )}</span>
                                  </div>`
                        }
                    </div>
                </td>
            </tr>
        `;
                })
                .join("");
            break;
    }
}

// Renderizar versão mobile para livros
function renderMobileLivros(data) {
    const container = document.getElementById("mobile-livros-list");
    const emptyState = document.getElementById("empty-state-livros");

    if (data.length === 0) {
        container.innerHTML = "";
        if (emptyState) emptyState.classList.remove("hidden");
        return;
    }

    if (emptyState) emptyState.classList.add("hidden");

    container.innerHTML = data
        .map((livro) => {
            const hasImage =
                livro.capa_url &&
                livro.capa_url !== "null" &&
                livro.capa_url !== "";

            return `
            <div class="mobile-book-card">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-20 rounded bg-base-200 flex items-center justify-center overflow-hidden border">
                            ${
                                hasImage
                                    ? `<img src="${livro.capa_url}" 
                                      alt="Capa de ${livro.nome}" 
                                      class="mobile-book-cover"
                                      onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                 <div class="w-full h-full flex items-center justify-center hidden bg-base-300">
                                     <span class="text-xs">${livro.capa}</span>
                                 </div>`
                                    : `<span class="text-xs">${livro.capa}</span>`
                            }
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm truncate">${
                            livro.nome
                        }</h3>
                        <p class="text-xs text-gray-600 mt-1">${
                            livro.editora
                        }</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            ${livro.autores
                                .map(
                                    (autor) =>
                                        `<span class="badge badge-xs">${autor}</span>`
                                )
                                .join("")}
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-semibold text-sm">€${
                                livro.preco
                            }</span>
                            <span class="text-xs text-gray-500">ISBN: ${
                                livro.isbn
                            }</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        })
        .join("");
}

// Função para mostrar/ocultar tabs
function showTab(tabName) {
    // Esconder todas as tabs
    document.getElementById("tab-livros").classList.add("hidden");
    document.getElementById("tab-autores").classList.add("hidden");
    document.getElementById("tab-editoras").classList.add("hidden");

    // Mostrar a tab selecionada
    document.getElementById("tab-" + tabName).classList.remove("hidden");

    // Atualizar estado visual dos cards
    const cards = document.querySelectorAll("#mainTabs .hover-card");
    cards.forEach((card) => {
        card.classList.remove("bg-primary", "text-primary-content");
        card.classList.add("bg-white");
    });

    // Destacar o card clicado
    event.currentTarget.classList.remove("bg-white");
    event.currentTarget.classList.add("bg-primary", "text-primary-content");
}

// Função de ordenação
function sortTable(tableType, columnIndex) {
    let data;
    switch (tableType) {
        case "livros":
            data = [...livrosData];
            break;
        case "autores":
            data = [...autoresData];
            break;
        case "editoras":
            data = [...editorasData];
            break;
    }

    if (currentSort.table === tableType && currentSort.column === columnIndex) {
        currentSort.direction =
            currentSort.direction === "asc" ? "desc" : "asc";
    } else {
        currentSort = {
            table: tableType,
            column: columnIndex,
            direction: "asc",
        };
    }

    updateSortIndicators(tableType, columnIndex);

    data.sort((a, b) => {
        let aValue, bValue;

        switch (tableType) {
            case "livros":
                aValue = Object.values(a)[columnIndex];
                bValue = Object.values(b)[columnIndex];
                break;
            case "autores":
            case "editoras":
                aValue = Object.values(a)[columnIndex];
                bValue = Object.values(b)[columnIndex];
                break;
        }

        if (!isNaN(aValue) && !isNaN(bValue)) {
            return currentSort.direction === "asc"
                ? aValue - bValue
                : bValue - aValue;
        }

        return currentSort.direction === "asc"
            ? String(aValue).localeCompare(String(bValue))
            : String(bValue).localeCompare(String(aValue));
    });

    renderTable(tableType, data);
}

// Atualizar indicadores visuais de ordenação
function updateSortIndicators(tableType, columnIndex) {
    const maxColumns = tableType === "livros" ? 8 : 2;
    for (let i = 0; i < maxColumns; i++) {
        const indicator = document.getElementById(`sort-${tableType}-${i}`);
        if (indicator) indicator.textContent = "↕";
    }

    const currentIndicator = document.getElementById(
        `sort-${tableType}-${columnIndex}`
    );
    if (currentIndicator) {
        currentIndicator.textContent =
            currentSort.direction === "asc" ? "↑" : "↓";
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
    currentSearch[tableType] = "";
    currentFilters[tableType] = {};
    const searchInput = document.getElementById(`search-${tableType}`);
    if (searchInput) searchInput.value = "";

    // Resetar selects
    const selects = document.querySelectorAll(`#tab-${tableType} select`);
    selects.forEach((select) => (select.value = ""));

    applyFiltersAndSearch(tableType);
}

// Atualizar contador de resultados
function updateResultCount(tableType, count) {
    const element = document.getElementById(`result-count-${tableType}`);
    if (!element) return;

    let total;
    switch (tableType) {
        case "livros":
            total = livrosData.length;
            break;
        case "autores":
            total = autoresData.length;
            break;
        case "editoras":
            total = editorasData.length;
            break;
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
        element.classList.remove("hidden");
    } else {
        element.classList.add("hidden");
    }
}
// Função para mostrar loading durante a exportação
function setupExportButton() {
    const exportButton = document.getElementById("btn-exportar-csv");

    if (exportButton) {
        exportButton.addEventListener("click", function (e) {
            const originalHTML = exportButton.innerHTML;

            // Mostrar loading
            exportButton.innerHTML =
                '<span class="loading loading-spinner loading-sm"></span> A gerar CSV...';
            exportButton.classList.add("btn-disabled");
            exportButton.style.opacity = "0.7";
            exportButton.style.pointerEvents = "none";

            // Reset após 8 segundos (caso algo corra mal)
            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove("btn-disabled");
                exportButton.style.opacity = "1";
                exportButton.style.pointerEvents = "auto";
            }, 8000);
        });
    }
}

// Inicializar quando a página carregar
document.addEventListener("DOMContentLoaded", function () {
    setupExportButton();

    // Sua inicialização existente...
    renderTable("livros", livrosData);
    renderMobileLivros(livrosData);
    renderTable("autores", autoresData);
    renderTable("editoras", editorasData);
    updateResultCount("livros", livrosData.length);
    updateResultCount("autores", autoresData.length);
    updateResultCount("editoras", editorasData.length);
});
