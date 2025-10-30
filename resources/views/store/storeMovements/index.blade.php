<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            حركات المستودع
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">


    <div class="py-12">
        <div class="filter-box" style="margin:20px 0; text-align:center;">
            <form method="GET" action="{{ route('storeMovements.index', $movement) }}" style="display:flex; gap:10px;flex-wrap: wrap;justify-content:center;">
                <select name="employee_id" style="padding:8px; border:1px solid #ccc; border-radius:6px; min-width:200px;">
                    <option value="">كل الموظفين</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->item?->name }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date" value="{{ $date }}" style="padding:8px; border:1px solid #ccc; border-radius:6px;">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>
        <div class="add btn">
            <a href="{{ route('storeMovements.create',$movement) }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>الموظف</th>
                        <th>من مستودع</th>
                        <th>إلى مستودع</th>
                        <th>نوع الحركة</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الأصناف</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($storeTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->user?->name }}</td>
                            <td>{{ $transaction->employee?->item?->name }}</td>
                            <td>{{ $transaction->fromStore?->name ?? '-' }}</td>
                            <td>{{ $transaction->toStore?->name ?? '-' }}</td>
                            <td>{{ $transaction->movement?->name ?? '-' }}</td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td>
                                @switch($transaction->status)
                                    @case('pending') غير معتمد  @break
                                    @case('approved') معتمد @break
                                    @case('cancelled') ملغاة @break
                                    @default -
                                @endswitch
                            </td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                                <ul>
                                    {{ $transaction->items->sum('count') }}

                                </ul>
                            </td>
                            <td>
                                <div class="actions">
                                         <a href="{{ route('storeMovements.edit', $transaction->id) }}" class="btn btn-worn">تعديل</a>



                                        <form id="delete-form-{{ $transaction->id }}" action="{{ route('storeMovements.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $transaction->id }})">
                                                حذف
                                            </button>
                                        </form>


                                    <a href="{{ route('storeMovements.show', $transaction->id) }}" class="btn btn-primary">عرض</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $storeTransactions->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>

