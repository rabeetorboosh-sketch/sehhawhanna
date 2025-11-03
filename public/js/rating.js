document.addEventListener("DOMContentLoaded", function () {
    const employeeSelect = document.querySelector('select[name="item_id"]');
    const units = document.querySelectorAll('#rate-unit');

    function updateVisibleUnits() {
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        const typeId = selectedOption.getAttribute('data-type');

        units.forEach(unit => {
            const input = unit.querySelector('input[name="percentage[]"]');
            unit.style.display = 'none';
            input.removeAttribute('required');

            if (unit.classList.contains('type' + typeId)) {
                unit.style.display = 'block';
                input.setAttribute('required', 'required');
            }
        });
    }

    employeeSelect.addEventListener('change', updateVisibleUnits);
});
