document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".dropdown-btn");
    const toggleBtn = document.getElementById("toggleSidebar");
    const sidebar = document.querySelector(".sidebar");

    // فتح/إغلاق القوائم
    dropdowns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const parent = btn.parentElement;
            parent.classList.toggle("active");

        });
    });

    // فتح/إغلاق السايدبار
    toggleBtn.addEventListener("click", () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle(" show");

        } else {
            sidebar.classList.toggle(" hidden");

        }
    });
});



