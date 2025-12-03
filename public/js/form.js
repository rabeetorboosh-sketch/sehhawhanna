document.addEventListener("DOMContentLoaded", function() {
    let alertBox = document.getElementById("success-alert");
    if(alertBox){
        setTimeout(() => {
            alertBox.classList.add("fade-out");
            setTimeout(() => alertBox.remove(), 800); // يحذفها نهائي
        }, 3000); // 3 ثواني
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const mainGroupSelect = document.getElementById('main_group');
    const subGroupSelect = document.getElementById('sub_group');


    if (mainGroupSelect && subGroupSelect){

        mainGroupSelect.addEventListener('change', () => {
            const mainGroupId = mainGroupSelect.value;

            if (!mainGroupId) {
                subGroupSelect.innerHTML = '<option disabled selected>اختر المجموعة الفرعية</option>';
                return;
            }

            fetch(`/subgroups/${mainGroupId}`)
                .then(response => response.json())
                .then(data => {
                    subGroupSelect.innerHTML = '<option disabled selected>اختر المجموعة الفرعية</option>';
                    data.forEach(subGroup => {
                        let option = document.createElement('option');
                        option.value = subGroup.id;
                        option.textContent = subGroup.name;
                        subGroupSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('خطأ في جلب البيانات:', error);
                });
        });

    }
});
