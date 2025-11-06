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

    @php
        $grandTotal = 0;
    @endphp

    {{-- عرض التجميع حسب المجموعات --}}
    @foreach($summary as $mainName => $mainData)
        <div class="subgroup-title table-title">
            {{ $mainName }}
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>المجموعة الفرعية</th>
                        <th>إجمالي الكمية</th>
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
                        <td style="text-align:right;">إجمالي {{ $mainName }}</td>
                        <td>{{ number_format($mainData['total'], 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @php $grandTotal += $mainData['total']; @endphp
    @endforeach


    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr class="total-row">
                    <th>الإجمالي العام</th>
                    <th>{{ number_format($grandTotal, 2) }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
@endsection
