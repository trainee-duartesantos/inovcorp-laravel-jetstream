// Mantemos apenas o botão Export CSV

document.addEventListener("DOMContentLoaded", function () {
    const exportButton = document.getElementById("btn-exportar-csv");

    if (exportButton) {
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
    if (!window.DATA) return;

    // =====================
    // 1️⃣ Livros
    // =====================
    const tbodyLivros = document.getElementById("tbody-livros");

    tbodyLivros.innerHTML = "";

    window.DATA.livros.forEach((livro) => {
        const autores = livro.autores.map((a) => a.nome).join(", ");
        const editora = livro.editora ? livro.editora.nome : "—";
        const capa = livro.capa_url
            ? `${window.DATA.storageBaseUrl}/${livro.capa_url}`
            : "https://via.placeholder.com/80x110?text=Sem+Imagem";

        tbodyLivros.innerHTML += `
            <tr>
                <td>${livro.id}</td>
                <td>${livro.isbn}</td>
                <td>${livro.nome}</td>
                <td>${editora}</td>
                <td>${autores}</td>
                <td>${livro.bibliografia ?? "—"}</td>
                <td><img src="${capa}" class="book-cover"></td>
                <td>${Number(livro.preco).toFixed(2)}€</td>
            </tr>
        `;
    });
    // =====================
    // 2️⃣ Autores
    // =====================
    const tbodyAutores = document.getElementById("tbody-autores");
    tbodyAutores.innerHTML = "";

    window.DATA.autores.forEach(autor => {
        const foto = autor.foto_url
            ? `${window.DATA.storageBaseUrl}/${autor.foto_url}`
            : "https://via.placeholder.com/200?text=Sem+Foto";

        tbodyAutores.innerHTML += `
            <tr>
                <td>${autor.nome}</td>
                <td>
                    <img src="${foto}" class="author-photo" alt="${autor.nome}">
                </td>
            </tr>
        `;
    });


    // =====================
    // 3️⃣ Editoras
    // =====================
    const tbodyEditoras = document.getElementById("tbody-editoras");
    tbodyEditoras.innerHTML = "";

    window.DATA.editoras.forEach(editora => {
        const logo = editora.logo_url
            ? `${window.DATA.storageBaseUrl}/${editora.logo_url}`
            : "https://via.placeholder.com/200?text=Sem+Logo";

        tbodyEditoras.innerHTML += `
            <tr>
                <td>${editora.nome}</td>
                <td>
                    <img src="${logo}" class="publisher-logo" alt="${editora.nome}">
                </td>
            </tr>
        `;
    });
});
