@extends('reports.customerRequest.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .customer-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #34495e;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            background-color: #f3f4f6;
            border-right: 3px solid #b8bcc1;
            border-radius: 8px;
        }
        .table-wrap { margin-bottom: 25px; }
    </style>

    @foreach($customers as $customer)
        @php
            $customerRequests = $requests->filter(fn($r) => $r->customer_id == $customer->id);
        @endphp

        @if($customerRequests->isNotEmpty())
            <div class="customer-title">{{ $customer->item?->name ?? 'عميل غير معروف' }}</div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>الموظف</th>
                            <th>الطريق</th>
                            <th>الوصف</th>
                            <th>الحالة</th>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>الكمية</th>
                            <th>المستخدم</th>
                            <th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customerRequests as $request)
                            @foreach($request->items as $item)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->employee?->item?->name ?? '-' }}</td>
                                    <td>{{ $request->salesRout?->name ?? '-' }}</td>
                                    <td>{{ $request->description ?? '-' }}</td>
                                    <td>{{ $request->status ?? '-' }}</td>
                                    <td>{{ $item->product?->item?->name ?? '-' }}</td>
                                    <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $request->user?->name ?? '-' }}</td>
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
@endsection
