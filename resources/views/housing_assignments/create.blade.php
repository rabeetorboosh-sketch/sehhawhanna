<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إنشاء عملية تسكين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('housing_assignments.store') }}" method="post">
            @csrf

            <div class="row-2">

                {{-- الوحدة --}}
                <div class="form-group">
                    <label>الوحدة السكنية</label>
                    <select name="housing_unit_id" id="unit-select" required>
                        <option disabled selected>اختر وحدة</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} - {{ $unit->unit_code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>تاريخ التسكين</label>
                    <input type="date" name="assignment_date" required>
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes"></textarea>
                </div>

            </div>

            <h3>الموظفون</h3>

            <div   style="border:1px solid gray;border-radius:10px;padding:5px">

                <div id="employees-wrapper">
                    <div class="employee-row">
                        <div  class="grid-tbl-5">

                            <div class="form-group">
                                <label>الموظف</label>
                                <select name="items[0][employee_id]" class="employee-select" required>
                                    <option disabled selected >اختر موظف</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>الغرفة</label>
                                <select name="items[0][housing_unit_room_id]" class="room-select" required>
                                    <option disabled selected >اختر وحدة أولاً</option>
                                </select>
                                <small class="beds-info" style="color:green;font-weight:bold"></small>
                            </div>

                            <div class="form-group">
                                <label>من تاريخ</label>
                                <input type="date" name="items[0][start_date]">
                            </div>

                            <div class="form-group">
                                <label>إلى تاريخ</label>
                                <input type="date" name="items[0][end_date]">
                            </div>

                            <button type="button" class="btn btn-danger remove-employee">-</button>

                        </div>
                    </div>

                </div>

                <button type="button" class="btn btn-worn add-employee">+</button>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>

        </form>
    </div>

    <script>
        let employeeIndex = 1;

        // تحديث الغرف حسب الوحدة
        function updateRoomsForAll(unitId) {

            let dateInput = document.querySelector('input[name="assignment_date"]');
            let assignmentDate = dateInput ? dateInput.value : '';


            fetch(`/housing/rooms/${unitId}?date=${assignmentDate}`)
                .then(res => res.json())
                .then(data => {

                    document.querySelectorAll('.room-select').forEach(select => {
                        select.innerHTML = `<option disabled selected>اختر غرفة</option>`;

                        data.forEach(room => {

                            let emptyBeds = room.empty_beds;
                            let disabled = emptyBeds <= 0 ? "disabled" : "";

                            select.innerHTML += `
                        <option value="${room.id}" data-empty="${emptyBeds}" ${disabled}>
                            ${room.room_name} — ${emptyBeds <= 0 ? 'ممتلئة' : 'أسرة فارغة: ' + emptyBeds}
                        </option>
                    `;
                        });
                    });
                });
        }


        // حدث عند اختيار غرفة
        function attachRoomChangeEvent(row) {

            let roomSelect = row.querySelector('.room-select');
            let info = row.querySelector('.beds-info');

            roomSelect.addEventListener('change', function () {

                let empty = this.options[this.selectedIndex].dataset.empty;

                if (empty <= 0) {
                    alert("❌ الغرفة ممتلئة بالكامل!");
                    this.value = "";
                    info.textContent = "";
                } else {
                    info.textContent = `عدد الأسرة الفارغة: ${empty}`;
                }
            });
        }

        // تطبيقه على الصف الأول
        document.querySelectorAll('.employee-row').forEach(row => {
            attachRoomChangeEvent(row);
        });

        // عند اختيار الوحدة
        document.getElementById('unit-select').addEventListener('change', function () {
            updateRoomsForAll(this.value);


        });

        // إضافة صف جديد
        document.querySelector('.add-employee').addEventListener('click', function () {

            let wrapper = document.getElementById('employees-wrapper');

            let row = document.createElement('div');
            row.classList.add('employee-row');

            row.innerHTML = `
                <div class="grid-tbl-5">

                    <div class="form-group">
                        <label>الموظف</label>
                        <select name="items[${employeeIndex}][employee_id]" class="employee-select" required>
                            <option disabled selected >اختر موظف</option>
                            @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->item->name }}</option>
                            @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>الغرفة</label>
            <select name="items[${employeeIndex}][housing_unit_room_id]" class="room-select" required>
                            <option disabled selected>اختر وحدة أولاً</option>
                        </select>
                        <small class="beds-info" style="color:green;font-weight:bold"></small>
                    </div>

                    <div class="form-group">
                        <label>من تاريخ</label>
                        <input type="date" name="items[${employeeIndex}][start_date]">
                    </div>

                    <div class="form-group">
                        <label>إلى تاريخ</label>
                        <input type="date" name="items[${employeeIndex}][end_date]">
                    </div>

                    <button type="button" class="btn btn-danger remove-employee">-</button>
                </div>
            `;

            // حذف الصف
            row.querySelector('.remove-employee').addEventListener('click', function () {
                row.remove();
            });

            wrapper.appendChild(row);

            // تفعيل حدث الغرف
            attachRoomChangeEvent(row);

            // تحديث الغرف إذا كانت الوحدة مختارة
            let unitId = document.getElementById('unit-select').value;
            if (unitId) updateRoomsForRow(unitId, row);
            employeeIndex++;
        });
        document.querySelector('input[name="assignment_date"]').addEventListener('change', function () {
            let unitId = document.getElementById('unit-select').value;
            if (unitId) updateRoomsForAll(unitId);
        });
        document.addEventListener('DOMContentLoaded', function () {

            let dateInput = document.querySelector('input[name="assignment_date"]');

            if (dateInput && !dateInput.value) {

                let today = new Date();
                let yyyy = today.getFullYear();
                let mm = String(today.getMonth() + 1).padStart(2, '0');
                let dd = String(today.getDate()).padStart(2, '0');

                dateInput.value = `${yyyy}-${mm}-${dd}`;
            }

        });
        function updateRoomsForRow(unitId, row) {

            let dateInput = document.querySelector('input[name="assignment_date"]');
            let assignmentDate = dateInput ? dateInput.value : '';

            fetch(`/housing/rooms/${unitId}?date=${assignmentDate}`)
                .then(res => res.json())
                .then(data => {

                    let roomSelect = row.querySelector('.room-select');
                    roomSelect.innerHTML = `<option disabled selected>اختر غرفة</option>`;

                    data.forEach(room => {

                        let emptyBeds = room.empty_beds;
                        let disabled = emptyBeds <= 0 ? "disabled" : "";

                        roomSelect.innerHTML += `
                    <option value="${room.id}" data-empty="${emptyBeds}" ${disabled}>
                        ${room.room_name} — ${emptyBeds <= 0 ? 'ممتلئة' : 'أسرة فارغة: ' + emptyBeds}
                    </option>
                `;
                    });
                });
        }
    </script>

</x-app-layout>
