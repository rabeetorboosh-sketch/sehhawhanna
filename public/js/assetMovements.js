function showSelect(selectEl) {
    selectEl.style.maxHeight = '300px';
    selectEl.style.border = '1px inset';
    selectEl.style.padding = '5px';
}
function hideSelect(selectEl) {
    selectEl.style.maxHeight = '0';
    selectEl.style.border = 'none';
    selectEl.style.padding = '0';
}

// ===== الأصل =====
(
    function setupAsset() {
    const input = document.querySelector('#assetInput');
    const hidden = document.querySelector('#assetId');
    const select = document.querySelector('#assetSelect');

    const originalOptions = Array.from(select.options).map(opt => ({
        value: opt.value,
        text: opt.text
    }));

    input.addEventListener('input', function () {
        const keyword = input.value.toLowerCase();
        select.innerHTML = '';

        const filtered = originalOptions.filter(opt =>
            opt.text.toLowerCase().includes(keyword)
        );

        if (filtered.length) {
            filtered.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.text = opt.text;
                select.appendChild(option);
            });
        } else {
            const emptyOption = document.createElement('option');
            emptyOption.text = 'لا يوجد نتائج';
            emptyOption.disabled = true;
            select.appendChild(emptyOption);
        }

        showSelect(select);
    });

    select.addEventListener('change', function () {
        const selected = select.selectedOptions[0];
        if (selected && selected.value) {
            input.value = selected.text;
            hidden.value = selected.value;
            hideSelect(select);
        }
    });

    hideSelect(select);
}
)();

// ===== فلترة مشتركة (من + إلى) =====
function setupFilter(inputId, hiddenId, selectId, buttonClass,hiddentype) {
    const input = document.querySelector(inputId);
    const hidden = document.querySelector(hiddenId);
    const hiddenType = document.querySelector(hiddentype);
    const select = document.querySelector(selectId);
    const filterButtons = document.querySelectorAll(buttonClass);

    const originalOptions = Array.from(select.options).map(opt => ({
        value: opt.value,
        text: opt.text,
        group: opt.dataset.group,
        dep: opt.dataset.dep
    }));

    let activeGroup = null; // المجموعة الحالية حسب الزر

    function renderOptions() {
        const keyword = input.value.toLowerCase();
        select.innerHTML = '';

        const filtered = originalOptions.filter(opt => {
            const matchGroup = activeGroup ? opt.group === activeGroup : true;
            const matchText = opt.text.toLowerCase().includes(keyword);
            return matchGroup && matchText;
        });

        if (filtered.length) {
            filtered.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.text = opt.text;
                option.dataset.group = opt.group;
                option.dataset.dep = opt.dep;
                select.appendChild(option);
            });
        } else {
            const emptyOption = document.createElement('option');
            emptyOption.text = 'لا يوجد نتائج';
            emptyOption.disabled = true;
            select.appendChild(emptyOption);
        }

        showSelect(select);
    }

    input.addEventListener('input', renderOptions);

    select.addEventListener('change', function () {
        const selected = select.selectedOptions[0];
        if (selected && selected.value) {
            input.value = selected.text;
            hidden.value = selected.value;
            hiddenType.value=selected.dataset.dep;
            hideSelect(select);

         }
    });

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            activeGroup = btn.dataset.type;
            renderOptions();
        });
    });

    renderOptions();
}

// ===== من =====
setupFilter('#fromInput', '#fromNo', '#fromSelect', '.filter-btn-from','#fromNoType');

// ===== إلى =====
setupFilter('#toInput', '#toNo', '#toSelect', '.filter-btn-to','#toNoType');
