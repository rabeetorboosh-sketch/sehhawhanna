
    document.addEventListener("DOMContentLoaded", function () {
    const tables = document.querySelectorAll("table.sortable");

    tables.forEach((table) => {
    table.querySelectorAll("th").forEach((header, columnIndex) => {
    header.style.cursor = "pointer";
    header.addEventListener("click", () => {

    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const isAsc = header.classList.contains("asc");

    // مسح علامات الفرز من كل العناوين
    table.querySelectorAll("th").forEach(th => th.classList.remove("asc", "desc"));

    // إضافة العلامة للعمود الحالي
    header.classList.toggle("asc", !isAsc);
    header.classList.toggle("desc", isAsc);

    // الترتيب
        rows.sort((a, b) => {
            let aText = a.children[columnIndex].innerText.trim();
            let bText = b.children[columnIndex].innerText.trim();

            // إعطاء قيمة خاصة للخلايا الفارغة
            if (aText === "" && bText === "") return 0;
            if (aText === "") return isAsc ? 1 : -1; // الفارغ يجي تحت لو تصاعدي
            if (bText === "") return isAsc ? -1 : 1; // الفارغ يجي تحت لو تنازلي

            // إذا كان رقم قارن كأرقام، غير كذا كـ نص
            const aVal = isNaN(aText) ? aText.toLowerCase() : parseFloat(aText);
            const bVal = isNaN(bText) ? bText.toLowerCase() : parseFloat(bText);

            if (aVal < bVal) return isAsc ? -1 : 1;
            if (aVal > bVal) return isAsc ? 1 : -1;
            return 0;
        });

        // إعادة إدخال الصفوف بالترتيب الجديد
    rows.forEach(row => table.querySelector("tbody").appendChild(row));
});
});
});
});

