<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة وحدة تقييم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('rating_units.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>اســـم الــوحــدة</label>
                    <input name="name" type="text" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>مـعـامـل الـضـرب</label>
                    <input name="multiply" type="number" step="0.01" autocomplete="off" required>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
</x-app-layout>
