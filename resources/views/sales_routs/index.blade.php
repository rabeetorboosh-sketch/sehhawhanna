<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            المسارات البيعية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('sales_routs.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>اسم المسار</th>
                        <th>تاريخ الإنشاء</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($salesRouts as $rout)
                        <tr>
                            <td>{{ $rout->id }}</td>
                            <td>{{ $rout->employee?->item?->name ?? '-' }}</td>
                            <td>{{ $rout->name }}</td>
                            <td>{{ $rout->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('sales_routs.edit', $rout->id) }}" class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $rout->id }}"
                                          action="{{ route('sales_routs.destroy', $rout->id) }}"
                                          method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $rout->id }})">
                                            حذف
                                        </button>
                                    </form>

                                    <a href="{{ route('sales_routs.show', $rout->id) }}" class="btn btn-primary">عرض</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- التصفح (Pagination) --}}
            @if (method_exists($salesRouts, 'links'))
                <div class="mt-4">
                    {{ $salesRouts->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>

    <script>
        function confirmDelete(id) {
            if (confirm('هل أنت متأكد من حذف هذا المسار؟')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
