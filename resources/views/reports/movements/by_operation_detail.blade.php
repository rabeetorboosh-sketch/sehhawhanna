@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
<style>
    /* ========== تنسيق التقرير الخارجي (باستثناء الجدول) ========== */

    /* اسم العملية (الحركة) */
    .operation-name {
        font-size: 1.4rem;
        font-weight: bold;
        color: #525255; /* أزرق ملكي */
        margin-top: 25px;
        margin-bottom: 10px;
        padding: 8px 15px;
        border-right: 2px solid #535a5c;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .dtl-group {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: 2px 0;
    }
    .dtl {
        background: #ffffff;
        box-shadow: 0 0 5px -2px gray;
        padding: 3px;border-radius: 5px;
    }

    /* إضافة فراغ بين الجداول */
    .table-wrap {
        margin-bottom: 30px;
    }

    /* تأثير بسيط عند المرور */
    .operation-name:hover, .store-name:hover {
        transform: translateX(1px);
        transition: 0.2s ease;

    }

</style>

        <div class="operation-name">
            {{ $operation->name }}
        </div>

        {{-- لكل حركة، نعرض المعاملات الخاصة بها --}}
        @foreach($transactions as $transaction)
            <div class=" dtl-group   ">
                <div class="dtl">
                {{ $transaction->FromStore?->name }} -> {{ $transaction->ToStore?->name }}
                </div>
                @if($transaction->employee)
                    <div class="dtl">
                    <span style="color: #777;"> الموظف: {{ $transaction->employee?->item?->name ?? $transaction->employee?->name }} </span>
                    </div>
                @endif
                <div class="dtl">
               المستخدم : {{$transaction->user->name}}
                </div>
<div class="dtl">
    <strong>التاريخ : {{$transaction->created_at}}</strong>
</div>

            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>من مخزن</th>
                            <th>إلى مخزن</th>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>الكمية</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transaction->items as $item)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->FromStore?->name }}</td>
                                <td>{{ $transaction->ToStore?->name }}</td>
                                <td>{{ $item->product?->item?->name ?? '-' }}</td>
                                <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                <td>{{ $item->count }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

@endsection
