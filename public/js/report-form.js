// فلترة المجموعات
const sectionSelect = document.getElementById('sectionSelect');
const mainSelect = document.getElementById('mainGroupSelect');
const subSelect = document.getElementById('subGroupSelect');

if (sectionSelect) {
    sectionSelect.addEventListener('change', () => {
        filterOptionsBy(sectionSelect.value, mainSelect, "section");
        mainSelect.value = '';
        subSelect.value = '';
        filterSubGroups();
        refreshFilters();
    });
}

if (mainSelect) {
    mainSelect.addEventListener('change', () => {
        filterSubGroups();
        refreshFilters();
    });
}

function filterSubGroups() {
    filterOptionsBy(mainSelect.value, subSelect, "mainGroup");
    subSelect.value = '';
}

function filterOptionsBy(value, select, attr) {
    Array.from(select.options).forEach(option => {
        if (!option.value) return;
        option.style.display = (!value || option.dataset[attr] === value) ? '' : 'none';
    });
}

function refreshFilters() {
    document.querySelectorAll('.itemsSelect').forEach(filterOptions);
    document.querySelectorAll('.control-unit-select').forEach(filterControlUnits);
}

function filterOptions(selectEl) {
    if (!sectionSelect) return;
    const section = sectionSelect.value;
    const main = mainSelect ? mainSelect.value : null;
    const sub = subSelect ? subSelect.value : null;

    selectEl.querySelectorAll('option').forEach(opt => {
        if (!opt.dataset.section) return;
        let visible = true;

        if (section && opt.dataset.section !== section) visible = false;
        if (main && opt.dataset.main && opt.dataset.main !== main) visible = false;
        if (sub && opt.dataset.sub && opt.dataset.sub !== sub) visible = false;

        opt.hidden = !visible;
        if (!visible && opt.selected) selectEl.selectedIndex = 0;
    });
}

function filterControlUnits(selectEl) {
    if (!sectionSelect) return;
    const section = sectionSelect.value;
    const main = mainSelect ? mainSelect.value : null;
    const sub = subSelect ? subSelect.value : null;

    selectEl.querySelectorAll('option').forEach(opt => {
        if (opt.value === "") return;
        let visible = true;

        if (section && opt.dataset.department && opt.dataset.department !== section) visible = false;
        if (main && opt.dataset.mainGroup && opt.dataset.mainGroup !== main) visible = false;
        if (sub && opt.dataset.subGroup && opt.dataset.subGroup !== sub) visible = false;

        opt.hidden = !visible;
        if (!visible && opt.selected) {
            selectEl.selectedIndex = 0;
            const index = selectEl.closest('.item-row').dataset.index;
            togglePhotoControl(selectEl, index);
            toggleUserControlUnit(selectEl, index);
        }
    });
}

// الصور
function togglePhotoControl(selectEl, index) {
    const row = selectEl.closest('.item-row');
    const photoControl = row.querySelector('.photo-control');
    const multiPhotoControl = row.querySelector('.multi-photo-control');
    const selectedOption = selectEl.options[selectEl.selectedIndex];
    const photoValue = selectedOption ? parseInt(selectedOption.dataset.photo) : 0;

    if (photoControl) photoControl.style.display = (photoValue === 1) ? 'block' : 'none';
    if (multiPhotoControl) multiPhotoControl.style.display = (photoValue === 2) ? 'block' : 'none';
}

function addPhotoField(btn) {
    const row = btn.closest('.item-row');
    const index = row.dataset.index;
    const container = row.querySelector('.photo-container');

    const newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.name = `items[${index}][control_unit_photos][]`;

    container.appendChild(newInput);
}

// وحدة يدوية
function toggleUserControlUnit(selectEl, index) {
    const row = selectEl.closest('.item-row');
    const userGroup = row.querySelector('.user-control-group');
    if (userGroup) userGroup.style.display = (selectEl.value) ? 'none' : 'block';
}

// إضافة وحذف البنود
let itemIndex = document.querySelectorAll('.item-row').length;

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add-item')) {
        const wrapper = document.getElementById('items-wrapper');
        const row = document.querySelector('.item-row');
        const clone = row.cloneNode(true);

        clone.dataset.index = itemIndex;

        clone.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${itemIndex}]`);
            }
            if (input.tagName === 'SELECT') input.selectedIndex = 0;
            else if (input.tagName === 'TEXTAREA') input.value = '';
            else if (input.type !== 'hidden') input.value = '';
        });

        bindEvents(clone, itemIndex);
        wrapper.appendChild(clone);
        itemIndex++;
    }

    if (e.target.classList.contains('remove-item')) {
        const wrapper = document.getElementById('items-wrapper');
        const rows = wrapper.querySelectorAll('.item-row');
        if (rows.length > 1) e.target.closest('.item-row').remove();
        else alert('يجب أن يبقى على الأقل بند واحد!');
    }

    if (e.target.classList.contains('add-photo-btn')) {
        addPhotoField(e.target);
    }
});

// ربط الأحداث
function bindEvents(itemElement, index) {
    const controlUnitSelect = itemElement.querySelector('.control-unit-select');
    if (controlUnitSelect) {
        controlUnitSelect.addEventListener('change', function () {
            togglePhotoControl(this, index);
            toggleUserControlUnit(this, index);
        });

        togglePhotoControl(controlUnitSelect, index);
        toggleUserControlUnit(controlUnitSelect, index);
        filterControlUnits(controlUnitSelect);
    }
}

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.item-row').forEach((row) => {
        const index = row.dataset.index;
        bindEvents(row, index);
    });
});
