document.querySelectorAll('.section-lbl').forEach(button => {
    button.addEventListener('click', function (e) {

        var grp = document.querySelectorAll('.group-lbl');

        grp.forEach(input => {
            input.style.display = 'none';  // إخفاء العنصر

        });


        var sec = document.querySelectorAll(`.sec${e.target.value}`);

        sec.forEach(input => {
            input.style.display = 'inline-block';  // تصحيح "disbly" إلى "display"
        });

    });
});


document.querySelectorAll('.group-lbl').forEach(button => {
    button.addEventListener('click', function (e) {
        var itm = document.querySelectorAll('.item-container');

        itm.forEach(input => {
            input.style.display = 'none';  // إخفاء العنصر

        });


        var inp_ut = document.querySelectorAll(`.grp${e.target.value}`);
        var firstChild = document.querySelector(`.grp${e.target.value} :first-child`);

        sumall(firstChild);
        inp_ut.forEach(input => {
            input.style.display = 'flex';
        });

    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.count').forEach(input => {
        input.addEventListener('input', function () {
            sumall(input);
        });
    });
});

function sumall(input) {
    const parent = input.closest('[class*="grp"]');
    if (!parent) return;

    const groupClass = Array.from(parent.classList).find(cls => cls.startsWith('grp'));
    if (!groupClass) return;
    const groupId = groupClass.replace('grp', '');

    let Stotal = 0;
    let PHtotal = 0;

    // اجمع الكميات من حقول الكمبيوتر
    document.querySelectorAll(`.S.grp${groupId} .count`).forEach(el => {
        const val = parseFloat(el.value);
        if (!isNaN(val)) Stotal += val;
    });

    // اجمع الكميات من حقول الجوال
    document.querySelectorAll(`.PH.grp${groupId} .count`).forEach(el => {
        const val = parseFloat(el.value);
        if (!isNaN(val)) PHtotal += val;
    });

    // تحديث الإجماليات العامة
    const summaryDivPC = document.querySelector(`.summaryPC`);
    const summaryDivPhone = document.querySelector(`.summaryPhone`);
    if (summaryDivPC) summaryDivPC.textContent = `الإجمالي: ${Stotal}`;
    if (summaryDivPhone) summaryDivPhone.textContent = `الإجمالي: ${PHtotal}`;

    // -----------------------------
    // 🔹 تحديث جدول الملخص للكمبيوتر
    // -----------------------------
    const tablePC = document.getElementById('group-summary-pc');
    if (tablePC) {
        const tbody = tablePC.querySelector('tbody');
        tbody.innerHTML = ''; // إعادة بناء الجدول من الصفر
        tablePC.style.display = 'table';

        const groups = document.querySelectorAll('.S[class*="grp"]');
        const groupTotals = {};

        groups.forEach(g => {
            const grpId = Array.from(g.classList).find(c => c.startsWith('grp')).replace('grp', '');
            const groupName = document.querySelector(`.group-lbl[value="${grpId}"]`)?.textContent.trim() || 'غير معروف';
            const unit = g.querySelector('.unit-select')?.selectedOptions[0]?.textContent || '';
            let total = 0;
            document.querySelectorAll(`.S.grp${grpId} .count`).forEach(el => {
                const val = parseFloat(el.value);
                if (!isNaN(val)) total += val;
            });
            if (total > 0) {
                groupTotals[grpId] = {name: groupName, unit: unit, total: total};
            }
        });

        Object.values(groupTotals).forEach(g => {
            const row = `<tr><td>${g.name}</td><td>${g.unit}</td><td>${g.total}</td></tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    // -----------------------------
    // 🔹 تحديث جدول الملخص للجوال
    // -----------------------------
    const tablePH = document.getElementById('group-summary-phone');
    if (tablePH) {
        const tbody = tablePH.querySelector('tbody');
        tbody.innerHTML = '';
        tablePH.style.display = 'table';

        const groups = document.querySelectorAll('.PH[class*="grp"]');
        const groupTotals = {};

        groups.forEach(g => {
            const grpId = Array.from(g.classList).find(c => c.startsWith('grp')).replace('grp', '');
            const groupName = document.querySelector(`.group-lbl[value="${grpId}"]`)?.textContent.trim() || 'غير معروف';
            const unit = g.querySelector('.unit-select')?.selectedOptions[0]?.textContent || '';
            let total = 0;
            document.querySelectorAll(`.PH.grp${grpId} .count`).forEach(el => {
                const val = parseFloat(el.value);
                if (!isNaN(val)) total += val;
            });
            if (total > 0) {
                groupTotals[grpId] = {name: groupName, unit: unit, total: total};
            }
        });

        Object.values(groupTotals).forEach(g => {
            const row = `<tr><td>${g.name}</td><td>${g.unit}</td><td>${g.total}</td></tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {

    function updateDefaultForSelect(selectEl) {
        // نحاول إيجاد default-wrapper داخل نفس الفورم (أو الوثيقة إذا لم يوجد)
        const form = selectEl.closest('form') || document;
        const wrapper = form.querySelector('.default-wrapper');

        if (!wrapper) return;

        const currentDiv = wrapper.querySelector('.current-default');
        const setDiv = wrapper.querySelector('.set_default');

        const selected = selectEl.value || '';
        const defaultStore = (selectEl.dataset.defaultStore || '').toString();

        // إذا تساوى المُختار مع الافتراضي -> أظهر "افتراضي" وأخفي خيار "وضع ك افتراضي"
        if (defaultStore !== '' && selected !== '' && selected === defaultStore) {
            if (currentDiv) currentDiv.style.display = '';
            if (setDiv) setDiv.style.display = 'none';
        } else {
            if (currentDiv) currentDiv.style.display = 'none';
            if (setDiv) setDiv.style.display = '';
        }

        // إذا ظهر خيار set_default ــ نلغي تحديده تلقائياً عند تغيير المستودع
        if (setDiv) {
            const cb = setDiv.querySelector('input[type="checkbox"]');
            if (cb) cb.checked = false;
        }
    }

    // استهدف كل العناصر التي تحمل الصنف store-select (نسخة الكمبيوتـر ونسخة الموبايل)
    document.querySelectorAll('.store-select').forEach(function (sel) {
        // تحديث مبدئي عند تحميل الصفحة
        updateDefaultForSelect(sel);

        // استمع لتغيّر الاختيار
        sel.addEventListener('change', function () {
            updateDefaultForSelect(sel);
        });
    });

});
document.addEventListener('DOMContentLoaded', function () {

    function syncEmployeeStore(empSelect) {
        if (!empSelect) return;

        // id مخزن في data-store الخاص بالخيار المحدد
        const selectedOption = empSelect.options[empSelect.selectedIndex];

        const empStoreId = selectedOption ? (selectedOption.dataset.store || '') : '';
        console.log(empStoreId);
        // نبحث عن حقل employee_store داخل نفس الفورم إن وجد، أو أول عنصر من النوع الموجود
        const form = empSelect.closest('form') || document;
        const empStoreSelect = form.querySelector('.employee-store-select');

        if (!empStoreSelect) return;

        if (empStoreId) {
            // ضع القيمة المرتبطة بالموظف
            empStoreSelect.value = empStoreId;
        } else {
            // لو الموظف ليس له مستودع مربوط خلّيه فارغاً (أو اختَر الخيار الافتراضي)
            empStoreSelect.value = '';
        }

        // فأرِق أيّ مستمعين آخرين
        empStoreSelect.dispatchEvent(new Event('change', {bubbles: true}));
    }

    // ربط كل الـ selects اللي تحمل الصنف employee-select
    document.querySelectorAll('.employee-select').forEach(function (sel) {
        // تهيئة على التحميل
        syncEmployeeStore(sel);

        // عندما يتغير اختيار الموظف
        sel.addEventListener('change', function () {
            syncEmployeeStore(sel);
        });
    });

});

    document.addEventListener("DOMContentLoaded", function () {

    // جميع الفورمات (PC + Phone)
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
    form.addEventListener("submit", function (e) {

    const employeeSelect = form.querySelector(".employee-select");
    const signatureInput = form.querySelector("#signature");

    if (!employeeSelect || !signatureInput) return;

    const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
    const employeeSignature = selectedOption.dataset.signature?.trim() || "";
    const userSignature = signatureInput.value.trim();

    // السماح إذا الحقل فاضي
    if (userSignature === "") return;

    // مقارنة التوقيع
    if (userSignature !== employeeSignature) {
    e.preventDefault();
    alert("❌ التوقيع غير صحيح. يرجى التأكد من إدخال التوقيع المطابق للموظف.");
}
});
});

});
