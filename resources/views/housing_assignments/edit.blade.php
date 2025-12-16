<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل عملية التسكين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('housing_assignments.update', $assignment->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">

                <div class="form-group">
                    <label>الوحدة السكنية</label>
                    <select name="housing_unit_id" id="unit-select" required>
                        <option disabled selected>اختر وحدة</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $assignment->housing_unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} - {{ $unit->unit_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>تاريخ التسكين</label>
                    <input type="date" name="assignment_date" id="assignment-date" value="{{ $assignment->assignment_date }}" required>
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ $assignment->notes }}</textarea>
                </div>

            </div>

            <h3>الموظفون</h3>
            <div style="border:1px solid gray;border-radius:10px;padding:5px">
                <div id="employees-wrapper">

                    @foreach($assignment->items as $i => $item)
                        <div class="employee-row">
                            <div class="grid-tbl-5">

                                <div class="form-group">
                                    <label>الموظف</label>
                                    <select name="items[{{ $i }}][employee_id]" class="employee-select" required>
                                        <option disabled>اختر موظف</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ $item->employee_id == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>الغرفة</label>
                                    <select name="items[{{ $i }}][housing_unit_room_id]" class="room-select" required>
                                        @foreach($assignment->unit?->rooms as $room)
                                            @php
                                                $emptyBeds = $room->empty_beds ?? $room->bed_count;
                                                $disabled = $emptyBeds <= 0 ? 'disabled' : '';
                                            @endphp
                                            <option value="{{ $room->id }}" {{ $item->housing_unit_room_id == $room->id ? 'selected' : '' }} {{ $disabled }}>
                                                {{ $room->room_name }} — {{ $emptyBeds <= 0 ? 'ممتلئة' : 'أسرة فارغة: '.$emptyBeds }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="beds-info" style="color:green;font-weight:bold"></small>
                                </div>

                                <div class="form-group">
                                    <label>من تاريخ</label>
                                    <input type="date" name="items[{{ $i }}][start_date]" value="{{ $item->start_date }}">
                                </div>

                                <div class="form-group">
                                    <label>إلى تاريخ</label>
                                    <input type="date" name="items[{{ $i }}][end_date]" value="{{ $item->end_date }}">
                                </div>

                                <button type="button" class="btn btn-danger remove-employee">-</button>

                            </div>
                        </div>
                    @endforeach

                </div>

                <button type="button" class="btn btn-worn add-employee">+</button>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>

        </form>
    </div>

    <script>
        let employeeIndex = {{ count($assignment->items) }};

        function updateRoomsForAll(unitId) {
            let assignmentDate = document.getElementById('assignment-date').value;

            fetch(`/housing/rooms/${unitId}?date=${assignmentDate}`)
                .then(res => res.json())
                .then(data => {
                    document.querySelectorAll('.room-select').forEach(select => {
                        let previousValue = select.value;
                        select.innerHTML = `<option disabled selected>اختر غرفة</option>`;
                        data.forEach(room => {
                            let disabled = room.empty_beds <= 0 ? 'disabled' : '';
                            select.innerHTML += `<option value="${room.id}" data-empty="${room.empty_beds}" ${disabled}>
                                ${room.room_name} — ${room.empty_beds <= 0 ? 'ممتلئة' : 'أسرة فارغة: ' + room.empty_beds}
                            </option>`;
                        });
                        if (previousValue) select.value = previousValue;
                    });
                });
        }

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

        document.querySelectorAll('.employee-row').forEach(row => attachRoomChangeEvent(row));

        document.getElementById('unit-select').addEventListener('change', function () {
            updateRoomsForAll(this.value);
        });

        document.getElementById('assignment-date').addEventListener('change', function () {
            let unitId = document.getElementById('unit-select').value;
            if (unitId) updateRoomsForAll(unitId);
        });

        document.querySelector('.add-employee').addEventListener('click', function () {
            let wrapper = document.getElementById('employees-wrapper');

            let row = document.createElement('div');
            row.classList.add('employee-row');
            row.innerHTML = `
                <div class="grid-tbl-5">
                    <div class="form-group">
                        <label>الموظف</label>
                        <select name="items[${employeeIndex}][employee_id]" class="employee-select" required>
                            <option disabled selected>اختر موظف</option>
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

            row.querySelector('.remove-employee').addEventListener('click', function () {
                row.remove();
            });

            wrapper.appendChild(row);
            attachRoomChangeEvent(row);

            let currentUnitId = document.getElementById('unit-select').value;
            if (currentUnitId) updateRoomsForAll(currentUnitId);

            employeeIndex++;
        });

        document.querySelectorAll('.remove-employee').forEach(btn => {
            btn.addEventListener('click', function () {
                btn.closest('.employee-row').remove();
            });
        });
    </script>

</x-app-layout>
