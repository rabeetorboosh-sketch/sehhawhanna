<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل تقييم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('ratings.update', $rating->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الموظف</label>
                    <select name="item_id" required>
                        <option value="">اختر</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->item->id }}" data-type="{{ $employee->type_id }}"
                                {{ $rating->item_id == $employee->item->id ? 'selected' : '' }}>
                                {{ $employee->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" name="date" value="{{ $rating->date }}" required>
                </div>
            </div>

            <h3>تفاصيل التقييم</h3>
            <div class="row-2">
                @foreach($units as $unit)
                    @php
                        $existing = $rating->items->firstWhere('rating_unit_id', $unit->id);
                    @endphp
                    <div class="form-group type{{ $unit->type_id }}" id="rate-unit">
                        <label>{{ $unit->name }}</label>
                        <input type="hidden" name="rating_unit_id[]" value="{{ $unit->id }}">
                        <input type="number" name="percentage[]" min="0" max="100"
                               value="{{ $existing->percentage ?? '' }}" placeholder="النسبة %">
                    </div>
                @endforeach
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>

    <script>
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
            updateVisibleUnits(); // لتفعيل الحالة عند التحميل المبدئي
        });
    </script>
</x-app-layout>
