<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل المسار البيعي ({{ $salesRout->name }})
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('sales_routs.update', $salesRout->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اســم الـمـســار</label>
                    <input name="name" type="text"
                           value="{{ old('name', $salesRout->name) }}"
                           autocomplete="off" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>المــوظــف</label>
                    <select name="employee_id" id="employee_id" class="emp-input" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ old('employee_id', $salesRout->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name ?? $emp->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('sales_routs.index') }}" class="btn btn-primary">إلغاء</a>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>
</x-app-layout>
