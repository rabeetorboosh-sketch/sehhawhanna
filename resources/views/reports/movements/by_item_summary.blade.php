@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .table-wrap { margin-bottom: 30px; }
        .product-name {
            font-weight: bold;
            color: #525255;
        }
    </style>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>الصنف</th>
                    <th>الوحدة</th>
                    <th>الإجمالي</th>
                </tr>
                </thead>
                <tbody>

                @foreach($summary as $productName => $units)
                    @foreach($units as $unitName => $total)

                        <tr>
                            <td>{{ $productName }}</td>
                            <td>{{ $unitName }}</td>

                            <td>{{  $total['count'] }}</td>
                        </tr>
                    @endforeach
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
