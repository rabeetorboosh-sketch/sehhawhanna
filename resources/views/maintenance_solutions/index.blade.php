<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            حلول طلبات الصيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('maintenance_solutions.create') }}">إضافة حل جديد <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الطلب</th>
                        <th>سبب المشكلة</th>
                        <th>حل المشكلة</th>
                        <th>الوقت المستغرق</th>
                        <th>الحل مؤقت</th>
                        <th>تم التسليم</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($solutions as $solution)
                        <tr>
                            <td>{{ $solution->id }}</td>
                            <td>{{ $solution->maintenanceRequest->id ?? '-' }}</td>
                            <td>{{ $solution->issue_reason }}</td>
                            <td>{{ $solution->solution_text ?? '-' }}</td>
                            <td>{{ $solution->time_spent ?? '-' }}</td>
                            <td>{{ $solution->temporary_solution ? 'نعم' : 'لا' }}</td>
                            <td>{{ $solution->delivered ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('maintenance_solutions.show', $solution->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('maintenance_solutions.edit', $solution->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $solution->id }}" action="{{ route('maintenance_solutions.destroy', $solution->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $solution->id }})">
                                            حذف
                                        </button>
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
