<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            استلام المهام
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('task_receipts.create') }}">إضافة استلام <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>الموظف</th>
                        <th>وقت الاستلام</th>
                        <th>مكتملة</th>
                        <th>محولة للادارة</th>
                        <th>الموقع</th>
                        <th> التقييم</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->id }}</td>
                            <td>{{ $receipt->assignment->task->item->name ?? '-' }}</td>
                            <td>{{ $receipt->employee->item->name ?? '-' }}</td>
                            <td>{{ $receipt->received_at }}</td>
                            <td>{{ $receipt->is_completed ? 'نعم' : 'لا' }}</td>
                            <td>{{ $receipt->forwarded_to_management ? 'نعم' : 'لا' }}</td>
                            <td>{{ $receipt->location ?? '-' }}</td>
                            <td>{{ $receipt->completion_percentage }}%</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('task_receipts.show', $receipt->id) }}" class="btn btn-primary">عرض</a>
                                    @if(Auth::user()->permissions('5-operations-receipts')?->can_update == 1)
                                    <a href="{{ route('task_receipts.edit', $receipt->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                    @if(Auth::user()->permissions('5-operations-receipts')?->can_delete == 1)
                                        <form id="delete-form-{{ $receipt->id }}" action="{{ route('task_receipts.destroy', $receipt->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $receipt->id }})">
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
    <script>
        function confirmDelete(id){
            if(confirm('هل أنت متأكد من حذف هذا الاستلام؟')){
                document.getElementById('delete-form-'+id).submit();
            }
        }
    </script>
</x-app-layout>
