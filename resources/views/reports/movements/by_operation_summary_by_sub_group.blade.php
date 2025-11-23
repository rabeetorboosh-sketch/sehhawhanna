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
        .divider {
            margin: 40px 0;
            border-top: 2px dashed #999;
        }
        th, td {
            white-space: nowrap;
        }

        /* --- تنسيق صناديق الأصناف داخل المجموعات الفرعية --- */
        .product-box {
            display: inline-block;
            padding: 6px 12px;
            background: #f7f7f7;
            margin: 4px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 0.9rem;
            white-space: nowrap;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }
        .product-name {
            font-weight: bold;
            color: #333;
            display: block;
        }
        .product-count {
            color: #0055aa;
            font-weight: bold;
            margin-right: 4px;
            display: block;
            text-align: center;
        }
    </style>





    <div class="summary-title">ملخص العمليات حسب المجموعات الفرعية</div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th style="width: 220px;">المجموعة الفرعية</th>
                    <th>الأصناف التابعة للمجموعة</th>
                    <th style="width: 160px;">إجمالي المجموعة</th>
                </tr>
                </thead>

                <tbody>
                @php $grandTotalGroups = 0; @endphp

                @foreach($summaryBySubGroup as $group)
                    <tr>
                        <td>{{ $group['sub_group_name'] }}</td>

                        <td>
                            @foreach($group['items'] as $item)
                                <div class="product-box">
                                    <span class="product-name">{{ $item['product_name'] }}</span>
                                    <span class="product-count">{{ $item['total_count'] }}</span>
                                </div>
                            @endforeach
                        </td>

                        <td><strong>{{ $group['sub_total'] }}</strong></td>

                        @php $grandTotalGroups += $group['sub_total']; @endphp
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="total-box" style="background:#e3f2fd;">
        إجمالي جميع المجموعات الفرعية: <strong>{{ $grandTotalGroups }}</strong>
    </div>


@endsection
