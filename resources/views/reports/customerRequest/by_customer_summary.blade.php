@extends('reports.customerRequest.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .customer-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 15px;
            padding: 10px 18px;
            background-color: #f3f4f6;
            border-right: 4px solid #b8bcc1;
            border-radius: 8px;
        }
        .table-wrap { margin-bottom: 25px; }
    </style>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th>الصنف</th>
                    <th>إجمالي الكمية</th>
                    <th>عدد الطلبات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($summary as $row)
                    <tr>
                        <td>{{ $row['customer_name'] }}</td>
                        <td>{{ $row['item_name'] }}</td>
                        <td>{{ $row['total_count'] }}</td>
                        <td>{{ $row['requests_count'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
