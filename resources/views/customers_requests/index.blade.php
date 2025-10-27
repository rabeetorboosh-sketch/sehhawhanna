<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            طلبات العملاء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('customersRequests.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>الموظف</th>
                        <th>العميل</th>
                        <th>خط السير</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>عدد العناصر</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->user?->name ?? '-' }}</td>
                            <td>{{ $req->employee?->item?->name ?? '-' }}</td>
                            <td>{{ $req->customer?->item?->name ?? '-' }}</td>
                            <td>{{ $req->salesRout?->name ?? '-' }}</td>
                            <td>{{ Str::limit($req->description, 50) }}</td>
                            <td>
                                @switch($req->status)
                                    @case('pending') غير معتمد @break
                                    @case('approved') معتمد @break
                                    @default -
                                @endswitch
                            </td>
                            <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $req->items->sum('count') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('customersRequests.edit', $req->id) }}" class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $req->id }}" action="{{ route('customersRequests.destroy', $req->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $req->id }})">
                                            حذف
                                        </button>
                                    </form>

                                    <a href="{{ route('customersRequests.show', $req->id) }}" class="btn btn-primary">عرض</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
