<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الموردين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('suppliers.create') }}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->item->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->item->mainGroup->name ?? '' }}</td>
                            <td>{{ $supplier->item->subGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $supplier->id }})">حذف</button>
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
