document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("show");
        });
    }

    // التعامل مع القوائم الفرعية
    const dropdownBtns = document.querySelectorAll(".dropdown-btn");

    dropdownBtns.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation(); // عشان ما ياثر على الاب

            const menuItem = this.parentElement;
            const siblings = menuItem.parentElement.querySelectorAll(".menu-item");

            // اغلق بس الإخوة بنفس المستوى


            // فتح/إغلاق الحالي
            menuItem.classList.toggle("active");
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const customMenu = document.getElementById('customMenu');
    const shortcutName = document.getElementById('shortcutName');
    const shortcutUrl = document.getElementById('shortcutUrl');

    sidebarLinks.forEach(link => {
        link.addEventListener("contextmenu", function(e) {
            e.preventDefault(); // منع قائمة المتصفح
            const url = this.href;
            const name = this.textContent.trim(); // اسم الرابط

            // وضع قيم الفورم
            shortcutName.value = name;
            shortcutUrl.value = url;

            // إظهار القائمة عند مكان الفأرة
            customMenu.style.top = e.pageY + "px";

            customMenu.style.display = "block";
        });
    });

    // التعامل مع الخيارات الأخرى (فتح تبويب جديد)
    customMenu.querySelector('[data-action="openNewTab"]').addEventListener("click", function() {
        const url = shortcutUrl.value;
        window.open(url, "_blank");
        customMenu.style.display = "none";
    });

    // إخفاء القائمة عند الضغط خارجها
    document.addEventListener("click", function() {
        customMenu.style.display = "none";
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const sidebarLinks = document.querySelectorAll('.upNav a');
    const customMenu = document.getElementById('navCustomMenu');
    const shortcutName = document.getElementById('navShortcutName');
    const shortcutUrl = document.getElementById('navShortcutUrl');

    sidebarLinks.forEach(link => {
        link.addEventListener("contextmenu", function(e) {
            e.preventDefault(); // منع قائمة المتصفح
            const url = this.href;
            const name = this.textContent.trim(); // اسم الرابط

            // وضع قيم الفورم
            shortcutName.value = name;
            shortcutUrl.value = url;

            // إظهار القائمة عند مكان الفأرة
            customMenu.style.top = e.pageY + "px";
            customMenu.style.left = e.pageX + "px";
            customMenu.style.display = "block";
        });
    });

    // التعامل مع الخيارات الأخرى (فتح تبويب جديد)
    customMenu.querySelector('[data-action="openNewTab"]').addEventListener("click", function() {
        const url = shortcutUrl.value;
        window.open(url, "_blank");
        customMenu.style.display = "none";
    });

    // إخفاء القائمة عند الضغط خارجها
    document.addEventListener("click", function() {
        customMenu.style.display = "none";
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    if (window.innerWidth > 768) {
        sidebar.classList.add("show");
    } else {
        sidebar.classList.remove("show");
    }
});
