<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الموظفين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        @if(Auth::user()->permissions('4-insertions-employees')?->can_create == 1)
        <div class="add btn">
            <a href="{{ route('employees.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        @endif
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الجنسية</th>
                        <th>العمر</th>
                        <th>رقم الهاتف</th>
                        <th> الايميل </th>
                        <th>الرقم التعريفي</th>
                        <th>تاريخ انتهاء الهوية</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->item->name }}</td>
                            <td>{{ $employee->nationality }}</td>
                            <td>{{ $employee->age }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->id_number }}</td>
                            <td>{{ $employee->id_expiry_date }}</td>
                            <td>{{ $employee->item->mainGroup->name ?? '' }}</td>
                            <td>{{ $employee->item->subGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('ratings.filter', $employee->item->id) }}" class="btn btn-primary">عرض التقييمات</a>
                                    @if(Auth::user()->permissions('4-insertions-employees')?->can_update == 1)
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                        @if(Auth::user()->permissions('4-insertions-employees')?->can_update == 1)
                                    <form id="delete-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $employee->id }})">
                                            حذف
                                        </button>
                                    </form>
                                        @endif
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
