@extends('reports.movements.filters')

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
        .dtl-group {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 2px 0;
        }
        .dtl {
            background: #fff;
            box-shadow: 0 0 5px -2px gray;
            padding: 3px 8px;
            border-radius: 5px;
        }
        .table-wrap { margin-bottom: 30px; }
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
                        <th>من مخزن</th>
                        <th>إلى مخزن</th>
                        <th>نوع العملية</th>
                        <th>الموظف</th>
                        <th>المستخدم</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry['transaction']->id }}</td>
                            <td>{{ $entry['transaction']->FromStore?->name ?? '-' }}</td>
                            <td>{{ $entry['transaction']->ToStore?->name ?? '-' }}</td>
                            <td>{{ $entry['transaction']->movement?->name ?? '-' }}</td>
                            <td>{{ $entry['transaction']->employee?->item?->name ?? ''}}</td>
                            <td>{{ $entry['transaction']->user?->name ?? '-' }}</td>
                            <td>{{ $entry['item']->unit?->unit?->name ?? '-' }}</td>
                            <td>{{ $entry['item']->count }}</td>
                            <td>{{ $entry['transaction']->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
