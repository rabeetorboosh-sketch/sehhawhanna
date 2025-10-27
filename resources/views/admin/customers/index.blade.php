<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            العملاء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <div class="py-12">
        @if(Auth::user()->permissions('8-insertions-customers')?->can_create == 1)

        <div class="add btn">
            <a href="{{ route('customers.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        @endif
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>الموظف</th>
                        <th>الخط</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->item->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->employee?->item?->name }}</td>
                            <td>{{ $customer->sales_rout?->name??'-' }}</td>
                            <td>{{ $customer->item->mainGroup->name ?? '' }}</td>
                            <td>{{ $customer->item->subGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->permissions('8-insertions-customers')?->can_update == 1)

                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                        @if(Auth::user()->permissions('8-insertions-customers')?->can_delete == 1)
                                        <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $customer->id }})">
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
