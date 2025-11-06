@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .subgroup-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #34495e;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            background-color: #f3f4f6;
            border-right: 3px solid #b8bcc1;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .table-wrap { margin-bottom: 25px; }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>

    {{-- تجميع حسب المجموعة الفرعية --}}
    @php
        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $subGroup = $item->product?->item?->subGroup?->name ?? 'غير محدد';
                $groupedTransactions[$subGroup][] = [
                    'transaction' => $transaction,
                    'item' => $item,
                ];
            }
        }
    @endphp

    @foreach($groupedTransactions as $subGroupName => $records)

        <div class="subgroup-title table-title">
            {{ $subGroupName }}( {{ $records[0]['item']->product?->item?->mainGroup?->name ?? '-' }} )
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>رقم العملية</th>
                        <th>نوع العملية</th>
                        <th>من المستودع</th>
                        <th>إلى المستودع</th>
                        <th>الصنف</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>الموظف</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $totalCount = 0; @endphp
                    @foreach($records as $record)
                        @php
                            $transaction = $record['transaction'];
                            $item = $record['item'];
                            $totalCount += $item->count;
                        @endphp
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->Movement?->name ?? '-' }}</td>
                            <td>{{ $transaction->FromStore?->name ?? '-' }}</td>
                            <td>{{ $transaction->ToStore?->name ?? '-' }}</td>
                            <td>{{ $item->product?->item?->name ?? '-' }}</td>
                            <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                            <td>{{ $item->count }}</td>
                            <td>{{ $transaction->employee?->item?->name ?? '-' }}</td>
                            <td>{{ $transaction->user?->name ?? '-' }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="6" style="text-align:right;">إجمالي الكمية للمجموعة:</td>
                        <td>{{ $totalCount }}</td>
                        <td colspan="3"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="{{asset('js/table.js')}}"></script>


    @endforeach
@endsection
