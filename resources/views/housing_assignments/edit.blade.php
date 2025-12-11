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

                {{-- اختيار الوحدة --}}
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
                    <input type="date" name="assignment_date" value="{{ $assignment->assignment_date }}" required>
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{ $assignment->notes }}</textarea>
                </div>

            </div>

            <!-- الموظفون -->
            <div class="form-group" style="border:1px solid gray;border-radius:10px;padding:5px">
                <h3>الموظفون</h3>

                <div id="employees-wrapper">

                    @foreach($assignment->items as $i => $item)
                        <div class="employee-row">
                            <div class="row-5">

                                <div class="form-group">
                                    <label>الموظف</label>
                                    <select name="items[{{ $i }}][employee_id]" class="employee-select">
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
                                    <select name="items[{{ $i }}][housing_unit_room_id]" class="room-select">
                                        @foreach($assignment->unit?->rooms as $room)
                                            <option value="{{ $room->id }}" {{ $item->housing_unit_room_id == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_name }} — أسرة: {{ $room->bed_count }}
                                            </option>
                                        @endforeach
                                    </select>
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
            fetch(`/housing/rooms/${unitId}`)
                .then(res => res.json())
                .then(data => {
                    document.querySelectorAll('.room-select').forEach(select => {
                        let previousValue = select.value;
                        select.innerHTML = `<option disabled selected>اختر غرفة</option>`;
                        data.forEach(room => {
                            select.innerHTML += `<option value="${room.id}">${room.room_name} — أسرة: ${room.bed_count}</option>`;
                        });
                        if (previousValue) select.value = previousValue;
                    });
                });
        }

        document.getElementById('unit-select').addEventListener('change', function () {
            updateRoomsForAll(this.value);
        });

        document.querySelector('.add-employee').addEventListener('click', function () {

            let wrapper = document.getElementById('employees-wrapper');

            let row = document.createElement('div');
            row.classList.add('employee-row');

            row.innerHTML = `
                <div class="row-5">
                    <div class="form-group">
                        <label>الموظف</label>
                        <select name="items[${employeeIndex}][employee_id]" class="employee-select">
                            <option disabled selected>اختر موظف</option>
                            @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->item->name }}</option>
                            @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>الغرفة</label>
            <select name="items[${employeeIndex}][housing_unit_room_id]" class="room-select">
                    <option disabled selected>اختر وحدة أولاً</option>
                </select>
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
            employeeIndex++;

            let currentUnitId = document.getElementById('unit-select').value;
            if (currentUnitId) {
                updateRoomsForAll(currentUnitId);
            }
        });

        document.querySelectorAll('.remove-employee').forEach(btn => {
            btn.addEventListener('click', function () {
                btn.closest('.employee-row').remove();
            });
        });
    </script>

</x-app-layout>
