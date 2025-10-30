document.addEventListener("DOMContentLoaded", function () {
    const tables = document.querySelectorAll("table.sortable");
    const hiddenColumns = new Set();

    // إنشاء منطقة أزرار فوق كل جدول
    tables.forEach((table) => {
        const hiddenContainer = document.createElement("div");
        hiddenContainer.classList.add("hidden-cols");
        table.parentNode.insertBefore(hiddenContainer, table);
        table._hiddenContainer = hiddenContainer;
    });

    // إعداد رؤوس الجداول
    tables.forEach((table) => {
        table.querySelectorAll("th").forEach((header, columnIndex) => {
            const colName = header.innerText.trim();

            // زر الإخفاء
            const hideBtn = document.createElement("span");
            hideBtn.textContent = "×";
            hideBtn.classList.add("hide-row");
            hideBtn.title = "إخفاء العمود";
            hideBtn.style.marginRight = "5px";
            hideBtn.style.cursor = "pointer";
            header.prepend(hideBtn);

            hideBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                hideColumn(colName);
            });

            header.style.cursor = "pointer";
            header.addEventListener("click", () => sortTable(table, columnIndex, header));
        });
    });

    // تحديث جميع مناطق الأزرار
    function refreshAllContainers() {
        tables.forEach((table) => {
            const c = table._hiddenContainer;
            c.innerHTML = "";
            hiddenColumns.forEach((colName) => {
                const btn = document.createElement("button");
                btn.textContent = colName;
                btn.dataset.col = colName;
                btn.classList.add("restore-btn");
                c.appendChild(btn);
                btn.addEventListener("click", () => restoreColumn(colName));
            });
        });
    }

    // إخفاء عمود باسم معين في كل الجداول
    function hideColumn(colName) {
        hiddenColumns.add(colName);

        tables.forEach((tbl) => {
            tbl.querySelectorAll("th").forEach((th, i) => {
                const name = th.innerText.replace("×", "").trim();
                if (name === colName) {
                    tbl.querySelectorAll("tr").forEach((row) => {
                        const cell = row.children[i];
                        if (cell) cell.style.display = "none";
                    });
                }
            });
        });

        refreshAllContainers();
    }

    // استعادة عمود باسم معين في كل الجداول
    function restoreColumn(colName) {
        hiddenColumns.delete(colName);

        tables.forEach((tbl) => {
            tbl.querySelectorAll("th").forEach((th, i) => {
                const name = th.innerText.replace("×", "").trim();
                if (name === colName) {
                    tbl.querySelectorAll("tr").forEach((row) => {
                        const cell = row.children[i];
                        if (cell) cell.style.display = "";
                    });
                }
            });
        });

        refreshAllContainers();
    }

    // فرز الأعمدة
    function sortTable(table, columnIndex, header) {
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
    }
});
