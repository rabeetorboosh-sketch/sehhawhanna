<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الوحدات السكنية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر الإضافة --}}
        <div class="add btn">
            <a href="{{ route('housing_units.create') }}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>كود الوحدة</th>
                        <th>اسم الوحدة</th>
                        <th>النوع</th>
                        <th>عدد المطابخ</th>
                        <th>عدد الحمامات</th>
                        <th>العنوان</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($housingUnits as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->unit_code }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->unit_type }}</td>
                            <td>{{ $unit->total_kitchens }}</td>
                            <td>{{ $unit->total_bathrooms }}</td>
                            <td>{{ $unit->address }}</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('housing_units.show', $unit->id) }}" class="btn btn-primary">عرض</a>

                                    <a href="{{ route('housing_units.edit', $unit->id) }}" class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $unit->id }}" action="{{ route('housing_units.destroy', $unit->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $unit->id }})">حذف</button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
