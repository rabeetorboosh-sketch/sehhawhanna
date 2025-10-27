<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل وحدة تقييم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('rating_units.update', $unit->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اســـم الــوحــدة</label>
                    <input name="name" type="text" value="{{ $unit->name }}" required>
                </div>
                <div class="form-group">
                    <label>مـعـامـل الـضـرب</label>
                    <input name="multiply" type="number" step="0.01" value="{{ $unit->multiply }}" required>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
        <script src="{{ asset('js/form.js') }}"></script>
    </div>
</x-app-layout>
