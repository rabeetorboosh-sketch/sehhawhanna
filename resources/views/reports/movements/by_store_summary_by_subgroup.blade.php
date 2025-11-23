@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <style>
        .store-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin: 25px 0 10px;
            color: #1e293b;
            padding: 8px 15px;
            background: #f1f5f9;
            border-right: 4px solid #cbd5e1;
            border-radius: 8px;
        }
        .group-row {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            background: #f8fafc;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
        }
        .group-name {
            width: 150px;
            font-weight: bold;
            color: #0f172a;
        }
        .item-box {
            padding: 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            margin-left: 12px;
            min-width: 150px;
            text-align: center;
            box-shadow: 0 1px 3px #00000010;
        }
        .item-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 1rem;
            color: #1e293b;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .item-table td, .item-table th {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            text-align: center;
        }
        .group-total {
            margin-right: auto;
            padding: 8px 14px;
            background: #e2f0ff;
            border-radius: 8px;
            font-weight: bold;
            border: 1px solid #90cdf4;
            min-width: 110px;
            text-align: center;
        }
    </style>

    @foreach($summary as $store)
        <div class="store-title">
            {{ $store['store_name'] }}
        </div>

        @foreach($store['groups'] as $g)
            <div class="group-row">

                <div class="group-name">
                    {{ $g['group_name'] }}
                </div>

                @foreach($g['items'] as $item)
                    <div class="item-box">
                        <div class="item-title">{{ $item['name'] }}</div>
                        <table class="item-table">
                            <tr>
                                <th>دخول</th>
                                <th>خروج</th>
                                <th>صافي</th>
                            </tr>
                            <tr>
                                <td>{{ $item['in'] }}</td>
                                <td>{{ $item['out'] }}</td>
                                <td>{{ $item['total'] }}</td>
                            </tr>
                        </table>
                    </div>
                @endforeach

                <div class="group-total">
                    <div class="item-box">
                        <div class="item-title">{{ $g['group_name'] }}</div>
                        <table class="item-table">
                            <tr>
                                <th>دخول</th>
                                <th>خروج</th>
                                <th>صافي</th>
                            </tr>
                            <tr>
                                <td>{{ $g['total_in'] }}</td>
                                <td>{{ $g['total_out'] }}</td>
                                <td>{{ $g['group_total'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        @endforeach
    @endforeach
@endsection
