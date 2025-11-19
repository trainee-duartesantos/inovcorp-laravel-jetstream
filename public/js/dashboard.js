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
});
