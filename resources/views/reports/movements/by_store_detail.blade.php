@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .store-title {
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

        .table-wrap {
            margin-bottom: 25px;
        }
    </style>


    {{-- تجميع المعاملات حسب المخزن --}}
    @foreach($stores as $store)
        @php
            $storeTransactions = $transactions->filter(function($t) use ($store) {
                return $t->from_store_id == $store->id || $t->to_store_id == $store->id;
            });
        @endphp

        @if($storeTransactions->isNotEmpty())
            <div class="store-title">
                {{ $store->name }}
            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>رقم العملية</th>
                            <th>نوع العملية</th>
                            <th>اتجاه العملية</th>
                            <th>من/الى</th>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>الكمية</th>
                            <th>الموظف</th>
                            <th>المستخدم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($storeTransactions as $transaction)
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->Movement?->name ?? '-' }}</td>
                                    <td>{{ $store->id==$transaction->ToStore?->id?'داخل':($store->id==$transaction->FromStore?->id?'خارج':'')  }} </td>
                                    <td>{{ $store->id==$transaction->ToStore?->id?$transaction->FromStore?->name??'':$transaction->ToStore?->name??''  }} </td>
                                    <td>{{ $item->product?->item?->name ?? '-' }}</td>
                                    <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $transaction->employee?->item?->name ?? '-' }}</td>
                                    <td>{{ $transaction->user?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
@endsection
