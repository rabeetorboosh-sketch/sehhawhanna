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
        .table-wrap { margin-bottom: 25px; }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>

    @foreach($stores as $store)
        @if(isset($summary[$store->name]))
            <div class="store-title">
                {{ $store->name }}
            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>إجمالي داخل</th>
                            <th>إجمالي خارج</th>
                            <th>الرصيد</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalIn = 0;
                            $totalOut = 0;
                        @endphp

                        @foreach($summary[$store->name] as $productName => $units)
                            @foreach($units as $unitName => $data)
                                @php
                                    $balance = $data['in'] - $data['out'];
                                    $totalIn += $data['in'];
                                    $totalOut += $data['out'];
                                @endphp
                                <tr>
                                    <td>{{ $productName }}</td>
                                    <td>{{ $unitName }}</td>
                                    <td>{{ $data['in'] }}</td>
                                    <td>{{ $data['out'] }}</td>
                                    <td>{{ $balance }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">الإجمالي العام:</td>
                            <td>{{ $totalIn }}</td>
                            <td>{{ $totalOut }}</td>
                            <td>{{ $totalIn - $totalOut }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
@endsection
