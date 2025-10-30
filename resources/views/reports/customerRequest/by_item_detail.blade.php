@extends('reports.customerRequest.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .product-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #525255;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #535a5c;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .table-wrap {
            margin-bottom: 30px;
        }
    </style>

    @foreach($grouped as $productName => $entries)
        <div class="product-name">
            {{ $productName }}
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>العميل</th>
                        <th>المستخدم</th>
                        <th>الطريق</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry['request']->id }}</td>
                            <td>{{ $entry['request']->employee?->item?->name ?? '-' }}</td>
                            <td>{{ $entry['request']->customer?->item?->name ?? '-' }}</td>
                            <td>{{ $entry['request']->user?->name ?? '-' }}</td>
                            <td>{{ $entry['request']->salesRout?->name ?? '-' }}</td>
                            <td>{{ $entry['request']->description ?? '-' }}</td>
                            <td>{{ $entry['request']->status ?? '-' }}</td>
                            <td>{{ $entry['item']->unit?->unit?->name ?? '-' }}</td>
                            <td>{{ $entry['item']->count }}</td>
                            <td>{{ $entry['request']->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
