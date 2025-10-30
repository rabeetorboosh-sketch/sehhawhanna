@extends('reports.customerRequest.filters')

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
            $employeeRequests = $requests->filter(function($r) use ($employee) {
                return $r->employee_id == $employee->id;
            });
        @endphp

        @if($employeeRequests->isNotEmpty())
            <div class="employee-title">
                {{ $employee->item?->name ?? 'موظف غير معروف' }}
            </div>

            @php
                $groupedItems = collect();

                foreach ($employeeRequests as $request) {
                    foreach ($request->items as $item) {
                        $productName = $item->product?->item?->name ?? 'بدون اسم صنف';
                        $unitName = $item->unit?->unit?->name ?? '-';
                        $key = $productName . '_' . $unitName;

                        $existing = $groupedItems->get($key, [
                            'product' => $productName,
                            'unit' => $unitName,
                            'count' => 0,
                        ]);

                        $existing['count'] += $item->count;

                        $groupedItems->put($key, $existing);
                    }
                }
            @endphp

            @if($groupedItems->isNotEmpty())
                <div class="table-wrap">
                    <div class="table-scroll">
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th>الصنف</th>
                                <th>الوحدة</th>
                                <th>إجمالي الكمية المطلوبة</th>
                                <th>عدد الطلبات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groupedItems as $row)
                                @php
                                    $requestCount = $employeeRequests->filter(function($r) use ($row) {
                                        return $r->items->contains(function($i) use ($row) {
                                            return ($i->product?->item?->name ?? '') === $row['product'];
                                        });
                                    })->count();
                                @endphp
                                <tr>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['unit'] }}</td>
                                    <td>{{ $row['count'] }}</td>
                                    <td>{{ $requestCount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">إجمالي الموظف</td>
                                <td>{{ $groupedItems->sum('count') }}</td>
                                <td>{{ $employeeRequests->count() }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    @endforeach

    <script src="{{ asset('js/table.js') }}"></script>
@endsection
