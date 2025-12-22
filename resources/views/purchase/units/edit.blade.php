<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل وحدة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('pur_units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اسم الوحدة</label>
                    <input name="name" type="text" autocomplete="off" value="{{ old('name', $unit->name) }}">
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn-save">تحديث</button>
                <button type="reset" class="btn-primary">إعادة تعيين</button>
            </div>
        </form>
    </div>

    <script src="{{asset('js/form.js')}}"></script>
</x-app-layout>
