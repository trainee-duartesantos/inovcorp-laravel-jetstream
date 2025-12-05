// public/js/dashboard.js

(function () {
    // --- Helpers básicos ----------------------------------------------------

    const state = {
        livros: {
            original: [],
            filtered: [],
            page: 1,
            perPage: 10,
            sortCol: null,
            sortDir: "asc",
            filters: {
                search: "",
                editora: "",
                autor: "",
                preco: "", // "0-20", "20-40", "40+"
            },
        },
        autores: {
            original: [],
            filtered: [],
            page: 1,
            perPage: 10,
            sortCol: null,
            sortDir: "asc",
            filters: {
                search: "",
            },
        },
        editoras: {
            original: [],
            filtered: [],
            page: 1,
            perPage: 10,
            sortCol: null,
            sortDir: "asc",
            filters: {
                search: "",
            },
        },
    };

    const debounceTimers = {};

    function escapeHtml(str) {
        if (str == null) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function imageUrl(path) {
        if (!path) return null;
        if (/^https?:\/\//i.test(path)) return path;
        const base = (window.DATA && window.DATA.storageBaseUrl) || "";
        if (!base) return path;
        return (
            base.replace(/\/+$/, "") + "/" + String(path).replace(/^\/+/, "")
        );
    }

    function formatPreco(value) {
        if (value == null || value === "") return "—";
        const num = parseFloat(String(value));
        if (isNaN(num)) return escapeHtml(value);
        return num.toFixed(2).replace(".", ",") + "€";
    }

    function parsePrecoNum(value) {
        if (value == null || value === "") return 0;
        const num = parseFloat(String(value));
        return isNaN(num) ? 0 : num;
    }

    // --- Normalização de dados vindos do PHP (@json) ------------------------

    function normalizarLivros(raw) {
        if (!Array.isArray(raw)) return [];
        return raw.map((livro) => {
            const autoresArr = Array.isArray(livro.autores)
                ? livro.autores
                : [];
            const autoresNomes = autoresArr
                .map((a) => a && a.nome)
                .filter(Boolean);

            return {
                id: livro.id,
                isbn: livro.isbn,
                nome: livro.nome,
                editora: livro.editora ? livro.editora.nome : "",
                autores: autoresNomes,
                bibliografia: livro.bibliografia || "",
                preco: livro.preco,
                capa_url: livro.capa_url || null,
            };
        });
    }

    function normalizarAutores(raw) {
        if (!Array.isArray(raw)) return [];
        return raw.map((autor) => ({
            id: autor.id,
            nome: autor.nome,
            foto_url: autor.foto_url ?? autor.foto ?? null,
        }));
    }

    function normalizarEditoras(raw) {
        if (!Array.isArray(raw)) return [];
        return raw.map((editora) => ({
            id: editora.id,
            nome: editora.nome,
            logo_url: editora.logo_url ?? editora.logo ?? null,
        }));
    }

    // --- Filtros / pesquisa / ordenação -------------------------------------

    function applyFilters(type) {
        const s = state[type];
        let data = s.original.slice();

        // 🔍 Pesquisa
        const search = (s.filters.search || "").toLowerCase().trim();
        if (search) {
            if (type === "livros") {
                data = data.filter((livro) => {
                    const haystack = [
                        livro.nome,
                        livro.isbn,
                        livro.editora,
                        livro.bibliografia,
                        (livro.autores || []).join(" "),
                    ]
                        .join(" ")
                        .toLowerCase();
                    return haystack.includes(search);
                });
            } else if (type === "autores") {
                data = data.filter((autor) =>
                    String(autor.nome).toLowerCase().includes(search)
                );
            } else if (type === "editoras") {
                data = data.filter((editora) =>
                    String(editora.nome).toLowerCase().includes(search)
                );
            }
        }

        // 🎯 Filtros específicos dos livros
        if (type === "livros") {
            const { editora, autor, preco } = s.filters;

            if (editora) {
                data = data.filter((livro) => livro.editora === editora);
            }

            if (autor) {
                data = data.filter(
                    (livro) => (livro.autores || []).indexOf(autor) !== -1
                );
            }

            if (preco) {
                data = data.filter((livro) => {
                    const p = parsePrecoNum(livro.preco);

                    // Faixa dinâmica “X-Y”
                    if (preco.includes("-")) {
                        const [min, max] = preco.split("-").map(parseFloat);
                        return p >= min && p <= max;
                    }

                    // Valor “Y+”
                    if (preco.endsWith("+")) {
                        const min = parseFloat(preco);
                        return p >= min;
                    }

                    return true;
                });
            }
        }

        // 🔢 Ordenação
        if (s.sortCol != null) {
            const col = s.sortCol;
            const dir = s.sortDir === "desc" ? -1 : 1;

            data.sort((a, b) => {
                let va, vb;

                if (type === "livros") {
                    switch (col) {
                        case 0:
                            va = a.id;
                            vb = b.id;
                            break;
                        case 1:
                            va = a.isbn;
                            vb = b.isbn;
                            break;
                        case 2:
                            va = a.nome;
                            vb = b.nome;
                            break;
                        case 3:
                            va = a.editora;
                            vb = b.editora;
                            break;
                        case 4:
                            va = (a.autores || []).join(", ");
                            vb = (b.autores || []).join(", ");
                            break;
                        case 5:
                            va = a.bibliografia;
                            vb = b.bibliografia;
                            break;
                        case 6:
                            va = parsePrecoNum(a.preco);
                            vb = parsePrecoNum(b.preco);
                            break;
                        default:
                            va = "";
                            vb = "";
                    }
                } else {
                    // autores / editoras — só 1 coluna de texto
                    va = a.nome;
                    vb = b.nome;
                }

                if (va == null) va = "";
                if (vb == null) vb = "";

                if (typeof va === "number" && typeof vb === "number") {
                    return (va - vb) * dir;
                }

                return (
                    String(va).localeCompare(String(vb), "pt", {
                        sensitivity: "base",
                    }) * dir
                );
            });
        }

        s.filtered = data;
        // Se eu mudar filtros ou ordenação, volto sempre à página 1
        if (s.page > Math.max(1, Math.ceil(data.length / s.perPage))) {
            s.page = 1;
        }

        render(type);
    }

    function setSearch(type, value) {
        state[type].filters.search = value || "";
        state[type].page = 1;
        applyFilters(type);
    }

    function changePage(type, page) {
        const s = state[type];
        const totalPages = Math.max(
            1,
            Math.ceil(s.filtered.length / s.perPage)
        );
        const newPage = Math.min(Math.max(1, page), totalPages);
        if (newPage === s.page) return;
        s.page = newPage;
        render(type);
    }

    function setSort(type, colIndex) {
        const s = state[type];

        if (s.sortCol === colIndex) {
            s.sortDir = s.sortDir === "asc" ? "desc" : "asc";
        } else {
            s.sortCol = colIndex;
            s.sortDir = "asc";
        }

        applyFilters(type);
    }

    function clearFiltersLivros() {
        const s = state.livros;
        s.filters = {
            search: "",
            editora: "",
            autor: "",
            preco: "",
        };
        s.sortCol = null;
        s.sortDir = "asc";
        s.page = 1;

        // Limpar campos do DOM (inputs / selects)
        const searchInput = document.getElementById("search-livros");
        if (searchInput) searchInput.value = "";

        const tab = document.getElementById("tab-livros");
        if (tab) {
            tab.querySelectorAll("select").forEach((sel) => {
                sel.selectedIndex = 0;
            });
        }

        const filtersBadge = document.getElementById("active-filters-livros");
        if (filtersBadge) filtersBadge.textContent = "";

        applyFilters("livros");
    }

    // --- Renderização -------------------------------------------------------

    function render(type) {
        if (type === "livros") renderLivros();
        else if (type === "autores") renderAutores();
        else if (type === "editoras") renderEditoras();
    }

    function renderLivros() {
        const s = state.livros;
        const tbody = document.getElementById("tbody-livros");
        const mobileList = document.getElementById("mobile-livros-list");
        const emptyState = document.getElementById("empty-state-livros");
        const resultSpan = document.getElementById("result-count-livros");
        const filtersBadge = document.getElementById("active-filters-livros");

        if (!tbody || !mobileList) return;

        const total = s.filtered.length;
        const perPage = s.perPage;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        const page = Math.min(Math.max(1, s.page), totalPages);
        s.page = page;

        const start = (page - 1) * perPage;
        const end = start + perPage;
        const pageItems = s.filtered.slice(start, end);

        // Empty state
        if (emptyState) {
            emptyState.classList.toggle("hidden", total > 0);
        }

        // Result count
        if (resultSpan) {
            if (!total) {
                resultSpan.textContent = "Nenhum livro encontrado";
            } else {
                resultSpan.textContent = `Mostrando ${start + 1}-${Math.min(
                    end,
                    total
                )} de ${total} livros`;
            }
        }

        // Filtros ativos badge
        if (filtersBadge) {
            const active = [];
            if (s.filters.editora) active.push(`Editora: ${s.filters.editora}`);
            if (s.filters.autor) active.push(`Autor: ${s.filters.autor}`);
            if (s.filters.preco) {
                const map = {
                    "0-20": "€0 - €20",
                    "20-40": "€20 - €40",
                    "40+": "€40+",
                };
                active.push(
                    `Preço: ${map[s.filters.preco] || s.filters.preco}`
                );
            }
            filtersBadge.textContent = active.join(" | ");
            filtersBadge.classList.toggle("hidden", active.length === 0);
        }

        // Desktop table
        let rowsHtml = "";
        pageItems.forEach((livro) => {
            const autoresStr = (livro.autores || []).join(", ");
            const capa = imageUrl(livro.capa_url);

            rowsHtml += `
                <tr>
                    <td>${livro.id ?? ""}</td>
                    <td>${escapeHtml(livro.isbn)}</td>
                    <td>${escapeHtml(livro.nome)}</td>
                    <td>${escapeHtml(livro.editora)}</td>
                    <td>${escapeHtml(autoresStr)}</td>
                    <td>${escapeHtml(livro.bibliografia)}</td>
                    <td>
                        ${
                            capa
                                ? `<img src="${capa}" alt="Capa de ${escapeHtml(
                                      livro.nome
                                  )}" class="book-cover">`
                                : ""
                        }
                    </td>
                    <td>${formatPreco(livro.preco)}</td>
                </tr>
            `;
        });
        tbody.innerHTML = rowsHtml;

        // Mobile cards
        let cardsHtml = "";
        pageItems.forEach((livro) => {
            const autoresStr = (livro.autores || []).join(", ");
            const capa = imageUrl(livro.capa_url);

            cardsHtml += `
                <div class="mobile-book-card flex gap-3">
                    <div>
                        ${
                            capa
                                ? `<img src="${capa}" alt="Capa de ${escapeHtml(
                                      livro.nome
                                  )}" class="mobile-book-cover">`
                                : ""
                        }
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold mb-1">${escapeHtml(
                            livro.nome
                        )}</h3>
                        <p class="text-sm text-gray-600 mb-1"><strong>ISBN:</strong> ${escapeHtml(
                            livro.isbn
                        )}</p>
                        <p class="text-sm text-gray-600 mb-1"><strong>Editora:</strong> ${escapeHtml(
                            livro.editora
                        )}</p>
                        <p class="text-sm text-gray-600 mb-1"><strong>Autores:</strong> ${escapeHtml(
                            autoresStr
                        )}</p>
                        <p class="text-sm text-gray-800 mb-1 line-clamp-3">${escapeHtml(
                            livro.bibliografia
                        )}</p>
                        <p class="font-bold mt-1">${formatPreco(
                            livro.preco
                        )}</p>
                    </div>
                </div>
            `;
        });
        mobileList.innerHTML = cardsHtml;

        renderPagination("livros");
    }

    function renderAutores() {
        const s = state.autores;
        const tbody = document.getElementById("tbody-autores");
        if (!tbody) return;

        const total = s.filtered.length;
        const perPage = s.perPage;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        const page = Math.min(Math.max(1, s.page), totalPages);
        s.page = page;

        const start = (page - 1) * perPage;
        const end = start + perPage;
        const pageItems = s.filtered.slice(start, end);

        let html = "";
        pageItems.forEach((autor) => {
            const foto =
                imageUrl(autor.foto_url) || "https://via.placeholder.com/80";
            html += `
                <tr>
                    <td>${escapeHtml(autor.nome)}</td>
                    <td>
                        ${
                            foto
                                ? `<div class="author-photo-container">
                                        <img src="${foto}" alt="Foto de ${escapeHtml(
                                      autor.nome
                                  )}" class="author-photo">
                                   </div>`
                                : "—"
                        }
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        renderPagination("autores");
    }

    function renderEditoras() {
        const s = state.editoras;
        const tbody = document.getElementById("tbody-editoras");
        if (!tbody) return;

        const total = s.filtered.length;
        const perPage = s.perPage;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        const page = Math.min(Math.max(1, s.page), totalPages);
        s.page = page;

        const start = (page - 1) * perPage;
        const end = start + perPage;
        const pageItems = s.filtered.slice(start, end);

        let html = "";
        pageItems.forEach((editora) => {
            const logo =
                imageUrl(editora.logo_url) || "https://via.placeholder.com/80";
            html += `
                <tr>
                    <td>${escapeHtml(editora.nome)}</td>
                    <td>
                        ${
                            logo
                                ? `<div class="publisher-logo-container">
                                        <img src="${logo}" alt="Logótipo de ${escapeHtml(
                                      editora.nome
                                  )}" class="publisher-logo">
                                   </div>`
                                : "—"
                        }
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        renderPagination("editoras");
    }

    function renderPagination(type) {
        const s = state[type];
        const total = s.filtered.length;
        const perPage = s.perPage;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        const page = s.page;

        const tableId =
            type === "livros"
                ? "table-livros"
                : type === "autores"
                ? "table-autores"
                : "table-editoras";

        const table = document.getElementById(tableId);
        if (!table) return;

        const containerId = "pagination-" + type;
        let container = document.getElementById(containerId);

        if (!container) {
            container = document.createElement("div");
            container.id = containerId;
            container.className =
                "pagination flex justify-center items-center gap-2 mt-4";
            const parent = table.parentElement || table;
            parent.appendChild(container);
        }

        if (!total) {
            container.innerHTML = "";
            return;
        }

        let html = "";

        // Botão anterior
        html += `<button class="btn btn-xs" data-page="${page - 1}" ${
            page === 1 ? "disabled" : ""
        }>«</button>`;

        // Páginas (compacto com "...")
        for (let p = 1; p <= totalPages; p++) {
            if (
                totalPages > 7 &&
                p !== 1 &&
                p !== totalPages &&
                Math.abs(p - page) > 1
            ) {
                if (p === 2 || p === totalPages - 1) {
                    html += `<span class="px-1">…</span>`;
                }
                continue;
            }

            html += `<button class="btn btn-xs ${
                p === page ? "btn-primary" : ""
            }" data-page="${p}">${p}</button>`;
        }

        // Botão seguinte
        html += `<button class="btn btn-xs" data-page="${page + 1}" ${
            page === totalPages ? "disabled" : ""
        }>»</button>`;

        html += `<span class="text-xs text-gray-500 ml-2">Página ${page} de ${totalPages}</span>`;

        container.innerHTML = html;

        container.querySelectorAll("button[data-page]").forEach((btn) =>
            btn.addEventListener("click", (e) => {
                const p = parseInt(e.currentTarget.dataset.page, 10);
                if (!isNaN(p)) changePage(type, p);
            })
        );
    }

    // --- Tabs Livros / Autores / Editoras -----------------------------------

    function setupTabs() {
        const tabIds = ["livros", "autores", "editoras"];

        window.showTab = function (tabName) {
            tabIds.forEach((name) => {
                const tab = document.getElementById("tab-" + name);
                if (!tab) return;
                tab.classList.toggle("hidden", name !== tabName);
            });
        };

        // Tab inicial
        showTab("livros");
    }

    // --- Exportar CSV: loading fake -----------------------------------------

    function setupExportButton() {
        const exportButton = document.getElementById("btn-exportar-csv");
        if (!exportButton) return;

        exportButton.addEventListener("click", function () {
            const originalHTML = exportButton.innerHTML;

            exportButton.innerHTML =
                '<span class="loading loading-spinner loading-sm"></span> A gerar CSV...';
            exportButton.classList.add("btn-disabled");
            exportButton.style.opacity = "0.7";
            exportButton.style.pointerEvents = "none";

            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove("btn-disabled");
                exportButton.style.opacity = "1";
                exportButton.style.pointerEvents = "auto";
            }, 8000);
        });
    }

    // --- Inicialização ------------------------------------------------------

    function initFromData() {
        if (!window.DATA) return;

        state.livros.original = normalizarLivros(window.DATA.livros || []);
        state.autores.original = normalizarAutores(window.DATA.autores || []);
        state.editoras.original = normalizarEditoras(
            window.DATA.editoras || []
        );

        state.livros.filtered = state.livros.original.slice();
        state.autores.filtered = state.autores.original.slice();
        state.editoras.filtered = state.editoras.original.slice();

        applyFilters("livros");
        applyFilters("autores");
        applyFilters("editoras");
    }

    document.addEventListener("DOMContentLoaded", function () {
        setupTabs();
        setupExportButton();
        initFromData();
    });

    // --- Funções globais usadas no HTML -------------------------------------

    // Pesquisa com debounce
    window.debouncedSearch = function (type, value) {
        clearTimeout(debounceTimers[type]);
        debounceTimers[type] = setTimeout(() => {
            setSearch(type, value);
        }, 200);
    };

    // Filtros dos selects
    window.filterTable = function (type, value, filterKey) {
        if (!state[type] || !state[type].filters) return;
        state[type].filters[filterKey] = value || "";
        state[type].page = 1;
        applyFilters(type);
    };

    // Ordenação ao clicar no header
    window.sortTable = function (type, colIndex) {
        setSort(type, colIndex);
    };

    // Limpar (Livros)
    window.clearSearch = function (type) {
        if (type === "livros") {
            clearFiltersLivros();
        } else {
            // autores/editoras — só limpa pesquisa
            setSearch(type, "");
        }
    };
})();
