<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            أنواع الموظفين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('employeeType.add') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table" id="employeeTypes">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>النوع</th>
                        <th>عمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employeeTypes as $employeeType)
                        <tr>
                            <td>{{ $employeeType->id }}</td>
                            <td>{{ $employeeType->name }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->permissions('4-insertions-employeesTypes')?->can_update == 1)
                                        <a href="{{ route('employeeType.edit', $employeeType->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                    @if(Auth::user()->permissions('4-insertions-employeesTypes')?->can_delete == 1)
                                        <form id="delete-form-{{ $employeeType->id }}" action="{{ route('employeeType.delete', $employeeType->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $employeeType->id }})">حذف</button>
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
