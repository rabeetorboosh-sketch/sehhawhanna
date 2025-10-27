document.querySelectorAll('.section-lbl').forEach(button => {
    button.addEventListener('click', function (e) {

        var inp = document.querySelectorAll('.item-container');
        var mingroup = document.querySelectorAll('.mainGroup-lbl');

        inp.forEach(input => {
            input.style.display = 'none';
        });
        mingroup.forEach(input => {
            input.style.display = 'none';
        });


        var inpt = document.querySelectorAll(`.grp${e.target.value}`);
        var mnGrp = document.querySelectorAll(`.maingrp${e.target.value}`);

        inpt.forEach(input => {
            input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
        });
        mnGrp.forEach(input => {
            input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
        });

    });
});
document.querySelectorAll('.mainGroup-lbl').forEach(button => {
    button.addEventListener('click', function (e) {

        var inp = document.querySelectorAll('.item-container');
        var subgroup = document.querySelectorAll('.subGroup-lbl');

        inp.forEach(input => {
            input.style.display = 'none';
        });
        subgroup.forEach(input => {
            input.style.display = 'none';
        });


        var inpt = document.querySelectorAll(`.minGrb${e.target.value}`);
        var sbGrp = document.querySelectorAll(`.subgrp${e.target.value}`);

        inpt.forEach(input => {
            input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
        });
        sbGrp.forEach(input => {
            input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
        });

    });
});
document.querySelectorAll('.subGroup-lbl').forEach(button => {
    button.addEventListener('click', function (e) {

        var inp = document.querySelectorAll('.item-container');

        inp.forEach(input => {
            input.style.display = 'none';
        });


        var inpt = document.querySelectorAll(`.subGroup${e.target.value}`);
        inpt.forEach(input => {
            input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
        });


    });
});


document.querySelectorAll('.correct').forEach(button => {
    button.addEventListener('change', function (e) {
        const cls = e.target.getAttribute('data-text');
        const txt = document.querySelector(`.txt${cls}`);
        const cuser = document.querySelector(`.cuser${cls}`);

        if (txt) {
            if (e.target.checked) {
                txt.classList.add('collapsed');
                txt.value = "";
                cuser.classList.add('collapsed');
                cuser.value = "";

            } else {
                txt.classList.remove('collapsed');
                cuser.classList.remove('collapsed');
            }
        }
    });
});

function addNewImageField(id) {
    let container = document.getElementById('multi-images-' + id);
    let inputId = 'images-extra-' + Date.now();

    let newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.name = `images[${id}][]`;
    newInput.accept = 'image/*';
    newInput.id = inputId;
    newInput.classList.add('hidden');

    let newLabel = document.createElement('label');
    newLabel.setAttribute('for', inputId);
    newLabel.style.cssText = `
        display: inline-block;
        padding: 5px 5px;
        background-color: #494c50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 8px;
        margin-left: 5px;
    `;
    newLabel.innerHTML = '<i class="fas fa-camera"></i> إضافة صورة';

    container.appendChild(newInput);
    container.appendChild(newLabel);
}


document.querySelectorAll('.items-add').forEach(button => {
    button.addEventListener('click', function (e) {

        const cls = e.target.getAttribute('data-text');
        const txt = document.querySelector(`.itm${cls}`);
        if (txt) {

            txt.classList.toggle('opened');
        }
    });
});
document.querySelectorAll('.items-save').forEach(button => {
    button.addEventListener('click', function (e) {

        const cls = e.target.getAttribute('data-text');
        const txt = document.querySelector(`.itm${cls}`);
        if (txt) {

            txt.classList.toggle('opened');
        }
    });
});
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".items").forEach(container => {
        const departmentSelect = container.querySelector(".department-chose");
        const mainGroupSelect = container.querySelector(".mainGroup-chose");
        const subGroupSelect = container.querySelector(".supGroup-chose");
        const searchInput = container.querySelector(".search-items");
        const items = container.querySelectorAll(".item-card");

        // نحتفظ بالخيارات الأصلية (لأننا راح نصفيها لاحقاً)
        const mainGroupOptions = Array.from(mainGroupSelect?.options || []);
        const subGroupOptions = Array.from(subGroupSelect?.options || []);

        // 🔥 جلب dept_id من grp...
        const grpDiv = container.closest("[class*='grp']");
        if (grpDiv) {
            const match = grpDiv.className.match(/grp(\d+)/);
            if (match) {
                const deptId = match[1];
                if (departmentSelect) {
                    departmentSelect.value = deptId;
                }
            }
        }

        // فلترة العناصر (الأصناف)
        function filterItems() {
            const dep = departmentSelect?.value || "";
            const main = mainGroupSelect?.value || "";
            const sub = subGroupSelect?.value || "";
            const search = searchInput?.value.toLowerCase() || "";

            items.forEach(item => {
                const itemDep = item.dataset.department;
                const itemMain = item.dataset.mainGroup;
                const itemSub = item.dataset.subGroup;
                const itemName = item.dataset.name;

                let visible = true;

                if (dep && itemDep !== dep) visible = false;
                if (main && itemMain !== main) visible = false;
                if (sub && itemSub !== sub) visible = false;
                if (search && !itemName.includes(search)) visible = false;

                item.style.display = visible ? "block" : "none";
            });
        }

        // فلترة المجموعات الرئيسية حسب القسم
        function filterMainGroups() {
            const dep = departmentSelect.value;
            mainGroupSelect.innerHTML = ""; // نفرغ القائمة
            mainGroupSelect.append(new Option("اختر المجموعة الرئيسية", ""));
            mainGroupOptions.forEach(opt => {
                if (!opt.value) return; // تخطي الافتراضي
                const optDep = opt.dataset.section;
                if (!dep || optDep === dep) {
                    mainGroupSelect.append(opt.cloneNode(true));
                }
            });
            mainGroupSelect.value = ""; // إعادة التعيين
            filterSubGroups(); // تحديث المجموعات الفرعية
            filterItems();     // تحديث الأصناف
        }

        // فلترة المجموعات الفرعية حسب القسم + المجموعة الرئيسية
        function filterSubGroups() {
            const main = mainGroupSelect.value;
            subGroupSelect.innerHTML = "";
            subGroupSelect.append(new Option("اختر المجموعة الفرعية", ""));
            subGroupOptions.forEach(opt => {
                if (!opt.value) return;
                const optMain = opt.dataset.mainGroup;
                if (!main || optMain === main) {
                    subGroupSelect.append(opt.cloneNode(true));
                }
            });
            subGroupSelect.value = "";
            filterItems();
        }

        // الأحداث
        if (departmentSelect) departmentSelect.addEventListener("change", filterMainGroups);
        if (mainGroupSelect) mainGroupSelect.addEventListener("change", filterSubGroups);
        if (subGroupSelect) subGroupSelect.addEventListener("change", filterItems);
        if (searchInput) searchInput.addEventListener("keyup", filterItems);

        // 🚀 تشغيل الفلاتر عند التحميل لأول مرة
        filterMainGroups();
    });
});


