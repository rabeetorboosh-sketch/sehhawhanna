<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة تقييم جديد
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('ratings.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الـمـوظـف </label>
                    <select name="item_id" required>
                        <option value="">اختر</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->item->id }}" data-type="{{$employee->type_id}}">{{ $employee->item?->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" name="date" required>
                </div>
            </div>

            <h3>تفاصيل التقييم</h3>
            <div class="row-2">
            @foreach($units as $unit)

                    <div class="form-group  type{{$unit->type_id}}" id="rate-unit">
                        <label>{{ $unit->name }}</label>
                        <input type="hidden" name="rating_unit_id[]" value="{{ $unit->id }}">
                        <input type="number" name="percentage[]" min="0" max="100" placeholder="النسبة %" required>
                    </div>

            @endforeach
            </div>
            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script src="{{asset('js/rating.js')}}"></script>
</x-app-layout>
