document.querySelectorAll('.section-lbl').forEach(button => {
    button.addEventListener('click', function(e) {

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
    button.addEventListener('click', function(e) {
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

    function sumall(input){


    const parent = input.closest('[class*="grp"]');
    if (!parent) return;


    const groupClass = Array.from(parent.classList).find(cls => cls.startsWith('grp'));
    if (!groupClass) return;
    const groupId = groupClass.replace('grp', '');
    let Stotal = 0;
    let PHtotal = 0;
    document.querySelectorAll(`.S.grp${groupId} .count`).forEach(el => {
    const val = parseFloat(el.value);
    if (!isNaN(val)) {
        Stotal += val;
}
});
        document.querySelectorAll(`.PH.grp${groupId} .count`).forEach(el => {
            const val = parseFloat(el.value);
            if (!isNaN(val)) {
                PHtotal += val;
            }
        });

    // عرض المجموع داخل الديف الخاص بالمجموعة
    const summaryDivPC = document.querySelector(`.summaryPC`);
    const summaryDivPhone = document.querySelector(`.summaryPhone`);
    if (summaryDivPC) {
    summaryDivPC.textContent = `الإجمالي: ${Stotal}`;
}
    if(summaryDivPhone){

    summaryDivPhone.textContent = `الإجمالي: ${PHtotal}`;

}

}
document.addEventListener('DOMContentLoaded', function() {

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
    document.querySelectorAll('.store-select').forEach(function(sel) {
        // تحديث مبدئي عند تحميل الصفحة
        updateDefaultForSelect(sel);

        // استمع لتغيّر الاختيار
        sel.addEventListener('change', function() {
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
        empStoreSelect.dispatchEvent(new Event('change', { bubbles: true }));
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
