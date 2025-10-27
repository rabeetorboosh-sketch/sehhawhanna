<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            قائمة التقارير
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('supervises.create') }}">
                إضافة تقرير <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الموظف</th>
                        <th>الهاتف</th>
                        <th>المشكلة</th>
                        <th>طريقة الحل</th>
                        <th>وقت المباشرة</th>
                        <th>الحالة</th>
                        <th>تحويل للإدارة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Supervises as $sup)
                        <tr>
                            <td>{{ $sup->id }}</td>
                            <td>{{ $sup->name }} </td>
                            <td>{{ $sup->employee->item->name }} </td>
                            <td>{{ $sup->phone }}</td>
                            <td>{{ $sup->issue }}</td>
                            <td>{{ $sup->solution_method }}</td>
                            <td>{{ $sup->start_time }}</td>
                            <td>
                                @if($sup->is_completed)
                                    <span class="text-green-600">تم الإنجاز</span>
                                @else
                                    <span class="text-yellow-600">قيد التنفيذ</span>
                                @endif
                            </td>
                            <td>
                                @if($sup->transferred_to_management)
                                    <span class="text-blue-600">نعم</span>
                                    <br>
                                    <small>{{ $sup->transfer_reason }}</small>
                                @else
                                    <span class="text-gray-500">لا</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('supervises.show', $sup->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('supervises.edit', $sup->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $sup->id }}" action="{{ route('supervises.destroy', $sup->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $sup->id }})">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $Supervises->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
