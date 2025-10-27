<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل تقييم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('ratings.update', $rating->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الموظف</label>
                    <select name="item_id" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->item->id }}" {{ $rating->item_id == $employee->item->id ? 'selected' : '' }}>
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
            @foreach($units as $unit)
                @php
                    $existing = $rating->items->firstWhere('rating_unit_id', $unit->id);
                @endphp
                <div class="row-2">
                    <div class="form-group">
                        <label>{{ $unit->name }}</label>
                        <input type="hidden" name="rating_unit_id[]" value="{{ $unit->id }}">
                        <input type="number" name="percentage[]" min="0" max="100" value="{{ $existing->percentage ?? '' }}" placeholder="النسبة %" required>
                    </div>
                </div>
            @endforeach

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>
</x-app-layout>
