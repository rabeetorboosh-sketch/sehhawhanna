<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة مسار بيعي
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('sales_routs.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>اســم الـمـســار</label>
                    <input name="name" type="text" value="{{ old('name') }}" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>المــوظــف</label>
                    <select name="employee_id" id="employee_id" class="emp-input" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name ?? $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
</x-app-layout>
