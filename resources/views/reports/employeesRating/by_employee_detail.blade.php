@extends('reports.movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .employee-title {
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

        .table-wrap {
            margin-bottom: 25px;
        }
    </style>

    {{-- تجميع المعاملات حسب الموظف --}}
    @foreach($employees as $employee)
        @php
            $employeeTransactions = $transactions->filter(function($t) use ($employee) {
                return $t->employee_id == $employee->id;
            });
        @endphp

        @if($employeeTransactions->isNotEmpty())
            <div class="employee-title table-title">
                {{ $employee->item?->name ?? 'بدون اسم موظف' }}
            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>رقم العملية</th>
                            <th>نوع العملية</th>
                            <th>من المخزن</th>
                            <th>إلى المخزن</th>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>الكمية</th>
                            <th>المستخدم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employeeTransactions as $transaction)
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->Movement?->name ?? '-' }}</td>
                                    <td>{{ $transaction->FromStore?->name ?? '-' }}</td>
                                    <td>{{ $transaction->ToStore?->name ?? '-' }}</td>
                                    <td>{{ $item->product?->item?->name ?? '-' }}</td>
                                    <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $transaction->user?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
    <script src="{{asset('js/table.js')}}"></script>

@endsection
