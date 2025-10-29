document.addEventListener("DOMContentLoaded", function () {
    const tables = document.querySelectorAll("table.sortable");

    tables.forEach((table) => {
        // إنشاء منطقة للأعمدة المخفية
        const hiddenContainer = document.createElement("div");
        hiddenContainer.classList.add("hidden-cols");
        table.parentNode.insertBefore(hiddenContainer, table);

        table.querySelectorAll("th").forEach((header, columnIndex) => {
            // زر إخفاء العمود
            const hideBtn = document.createElement("span");
            hideBtn.textContent = "×";
            hideBtn.classList.add("hide-row");
            hideBtn.title = "إخفاء العمود";
            header.appendChild(hideBtn);

            hideBtn.addEventListener("click", (e) => {
                e.stopPropagation();

                // إخفاء الخلايا في هذا العمود
                table.querySelectorAll("tr").forEach((row) => {
                    const cell = row.children[columnIndex];
                    if (cell) cell.style.display = "none";
                });

                // إنشاء زر لإعادة إظهار العمود
                const restoreBtn = document.createElement("button");
                restoreBtn.textContent = header.textContent.replace("×", "").trim();
                restoreBtn.classList.add("restore-btn");
                hiddenContainer.appendChild(restoreBtn);

                restoreBtn.addEventListener("click", () => {
                    table.querySelectorAll("tr").forEach((row) => {
                        const cell = row.children[columnIndex];
                        if (cell) cell.style.display = "";
                    });
                    restoreBtn.remove();
                });
            });

            // فرز الأعمدة
            header.style.cursor = "pointer";
            header.addEventListener("click", () => {
                const rows = Array.from(table.querySelectorAll("tbody tr"));
                const isAsc = header.classList.contains("asc");

                table.querySelectorAll("th").forEach(th => th.classList.remove("asc", "desc"));
                header.classList.toggle("asc", !isAsc);
                header.classList.toggle("desc", isAsc);

                rows.sort((a, b) => {
                    let aText = a.children[columnIndex].innerText.trim();
                    let bText = b.children[columnIndex].innerText.trim();

                    if (aText === "" && bText === "") return 0;
                    if (aText === "") return isAsc ? 1 : -1;
                    if (bText === "") return isAsc ? -1 : 1;

                    const aVal = isNaN(aText) ? aText.toLowerCase() : parseFloat(aText);
                    const bVal = isNaN(bText) ? bText.toLowerCase() : parseFloat(bText);

                    if (aVal < bVal) return isAsc ? -1 : 1;
                    if (aVal > bVal) return isAsc ? 1 : -1;
                    return 0;
                });

                rows.forEach(row => table.querySelector("tbody").appendChild(row));
            });
        });
    });
});
