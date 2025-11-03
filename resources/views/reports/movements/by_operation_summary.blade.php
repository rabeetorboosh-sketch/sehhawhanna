@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <style>
        .summary-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #444;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #555;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .total-box {
            background: #f7f7f7;
            border: 1px solid #ddd;
            padding: 8px 15px;
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
        }
    </style>

    <div class="summary-title">ملخص العمليات حسب الأصناف</div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الصنف</th>
                    <th>عدد المخازن المشاركة</th>
                    <th>إجمالي الكمية</th>
                </tr>
                </thead>
                <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($summary as $index => $row)
                    @php $grandTotal += $row['total_count']; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['store_count'] }}</td>
                        <td>{{ $row['total_count'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="total-box">
        الإجمالي العام لجميع الأصناف: <span>{{ $grandTotal }}</span>
    </div>
@endsection
