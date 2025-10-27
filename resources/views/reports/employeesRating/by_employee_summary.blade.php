@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .employee-title {
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

        .store-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1e293b;
            margin-top: 15px;
            margin-bottom: 8px;
            padding: 6px 15px;
            background-color: #f8fafc;
            border-right: 3px solid #cbd5e1;
            border-radius: 6px;
        }

        .table-wrap {
            margin-bottom: 25px;
        }

        tfoot tr {
            background-color: #f1f5f9;
            font-weight: bold;
        }
    </style>

    {{-- عرض كل موظف --}}
    @foreach($employees as $employee)
        @php
            $employeeStores = $employee->stores;
        @endphp

        @if($employeeStores->isNotEmpty())
            <div class="employee-title">
                {{ $employee->item?->name ?? 'موظف غير معروف' }}
            </div>

            {{-- عرض كل مستودع تابع للموظف --}}
            @php
                $employeeTotal = collect();
            @endphp

            @foreach($employeeStores as $store)
                @php
                    // تصفية الحركات التي تخص هذا المستودع سواء داخلة أو خارجة
                    $storeTransactions = $transactions->filter(function($t) use ($store) {
                        return $t->from_store_id == $store->id || $t->to_store_id == $store->id;
                    });

                    $groupedItems = collect();

                    foreach ($storeTransactions as $transaction) {
                        foreach ($transaction->items as $item) {
                            $productName = $item->product?->item?->name ?? 'بدون اسم صنف';
                            $unitName = $item->unit?->unit?->name ?? '-';
                            $key = $productName . '_' . $unitName;

                            $existing = $groupedItems->get($key, [
                                'product' => $productName,
                                'unit' => $unitName,
                                'in' => 0,
                                'out' => 0,
                            ]);

                            // إذا العملية تخص هذا المستودع كـ "إلى" فهي دخول، وإذا كـ "من" فهي خروج
                            if ($transaction->to_store_id == $store->id) {
                                $existing['in'] += $item->count;
                            } elseif ($transaction->from_store_id == $store->id) {
                                $existing['out'] += $item->count;
                            }

                            $groupedItems->put($key, $existing);
                        }
                    }
                @endphp

                @if($groupedItems->isNotEmpty())
                    <div class="table-title">
                        {{ $store->name ?? 'بدون اسم مستودع' }}
                    </div>

                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table sortable">
                                <thead>
                                <tr>
                                    <th>الصنف</th>
                                    <th>الوحدة</th>
                                    <th>الداخل</th>
                                    <th>الخارج</th>
                                    <th>الصافي</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $storeTotals = ['in'=>0, 'out'=>0];
                                @endphp

                                @foreach($groupedItems as $row)
                                    @php
                                        $net = $row['in'] - $row['out'];
                                        $storeTotals['in'] += $row['in'];
                                        $storeTotals['out'] += $row['out'];
                                    @endphp
                                    <tr>
                                        <td>{{ $row['product'] }}</td>
                                        <td>{{ $row['unit'] }}</td>
                                        <td>{{ $row['in'] }}</td>
                                        <td>{{ $row['out'] }}</td>
                                        <td>{{ $net }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2">إجمالي المستودع</td>
                                    <td>{{ $storeTotals['in'] }}</td>
                                    <td>{{ $storeTotals['out'] }}</td>
                                    <td>{{ $storeTotals['in'] - $storeTotals['out'] }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @php
                        // نجمع للإجمالي الكلي للموظف
                        foreach($groupedItems as $key => $data) {
                            $existing = $employeeTotal->get($key, [
                                'product' => $data['product'],
                                'unit' => $data['unit'],
                                'in' => 0,
                                'out' => 0,
                            ]);
                            $existing['in'] += $data['in'];
                            $existing['out'] += $data['out'];
                            $employeeTotal->put($key, $existing);
                        }
                    @endphp
                @endif
            @endforeach

            {{-- إجمالي المستودعات للموظف --}}
            @if($employeeTotal->isNotEmpty())
                <div class="store-title">
                    🔸 إجمالي جميع مستودعات {{ $employee->item?->name ?? 'الموظف' }}
                </div>

                <div class="table-wrap">
                    <div class="table-scroll">
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th>الصنف</th>
                                <th>الوحدة</th>
                                <th>إجمالي الداخل</th>
                                <th>إجمالي الخارج</th>
                                <th>الفارق</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employeeTotal as $row)
                                <tr>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['unit'] }}</td>
                                    <td>{{ $row['in'] }}</td>
                                    <td>{{ $row['out'] }}</td>
                                    <td>{{ $row['in'] - $row['out'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
    <script src="{{asset('js/table.js')}}"></script>
@endsection
