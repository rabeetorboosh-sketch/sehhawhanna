<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة مخزن
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('stores.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>اســم الـمـخـزن</label>
                    <input name="name" type="text" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>الــــــنـــــــوع</label>
                    <input name="type" type="text" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>الـــــــمـــوقــع</label>
                    <input name="location" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>ربـط بـمـوظـف</label>
                    <select name="employee_id" id="user_id" class="emp-input" >
                        <option value="" > اختر اسم الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"  {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->item?->name }}</option>
                        @endforeach
                    </select>
                </div>


                   <input name="branch_id" type="hidden" value="1">

            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
</x-app-layout>
