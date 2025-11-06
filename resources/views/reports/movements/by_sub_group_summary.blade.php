@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .main-title {font-size:1.3rem;font-weight:bold;color:#2c3e50;margin-top:20px;}
        .sub-table {margin-right:25px;margin-top:10px;}
        .total-row {background:#f8f8f8;font-weight:bold;}
    </style>

    @php
        $grandIn = $grandOut = $grandNet = 0;
    @endphp

    @foreach($summary as $mainName => $mainData)
        <div class="main-title">{{ $mainName }}</div>
        <div class="sub-table">
            <table class="table">
                <thead>
                <tr>
                    <th>المجموعة الفرعية</th>
                    <th> الكمية   </th>

                </tr>
                </thead>
                <tbody>
                @foreach($mainData['subGroups'] as $subName => $subData)
                    <tr>
                        <td>{{ $subName }}</td>
                        <td>{{ number_format($subData['total'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>إجمالي {{ $mainName }}</td>
                    <td>{{ number_format($mainData['total'], 2) }}</td>

                </tr>
                </tbody>
            </table>
        </div>

        @php
            $grandIn += $mainData['total'];

        @endphp
    @endforeach

    <table class="table" style="margin-top:30px">
        <thead>
        <tr class="total-row">
            <th>الإجمالي العام</th>
            <th>{{ number_format($grandIn, 2) }}</th>

        </tr>
        </thead>
    </table>
@endsection
