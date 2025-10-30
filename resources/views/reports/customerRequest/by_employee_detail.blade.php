@extends('reports.customerRequest.filters')

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

    {{-- تجميع الطلبات حسب الموظف --}}
    @foreach($employees as $employee)
        @php
            $employeeRequests = $requests->filter(function($r) use ($employee) {
                return $r->employee_id == $employee->id;
            });
        @endphp

        @if($employeeRequests->isNotEmpty())
            <div class="employee-title table-title">
                {{ $employee->item?->name ?? 'بدون اسم موظف' }}
            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المستخدم</th>
                            <th>العميل</th>
                            <th>الطريق</th>
                            <th>الوصف</th>
                            <th>الحالة</th>
                            <th>الصنف</th>
                            <th>الكود</th>
                            <th>الوحدة</th>
                            <th>الكمية</th>
                            <th>تاريخ الإنشاء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employeeRequests as $request)
                            @foreach($request->items as $item)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->user?->name ?? '-' }}</td>
                                    <td>{{ $request->customer?->item?->name ?? '-' }}</td>
                                    <td>{{ $request->salesRout?->name ?? '-' }}</td>
                                    <td>{{ $request->description ?? '-' }}</td>
                                    <td>{{ $request->status ?? '-' }}</td>
                                    <td>{{ $item->product?->item?->name ?? '-' }}</td>
                                    <td>{{ $item->product?->code ?? '-' }}</td>
                                    <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    <script src="{{ asset('js/table.js') }}"></script>
@endsection
