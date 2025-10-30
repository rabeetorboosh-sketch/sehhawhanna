@extends('reports.customerRequest.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .operation-name {
            font-size: 1.4rem;
            font-weight: bold;
            color: #525255;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #535a5c;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .dtl-group {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 2px 0;
        }

        .dtl {
            background: #ffffff;
            box-shadow: 0 0 5px -2px gray;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .table-wrap {
            margin-bottom: 30px;
        }

        .operation-name:hover {
            transform: translateX(1px);
            transition: 0.2s ease;
        }
    </style>

    <div class="operation-name">
        طلبات العملاء - تفاصيل العمليات
    </div>

    {{-- عرض كل طلب --}}
    @foreach($requests as $request)
        <div class="dtl-group">
            @if($request->employee)
                <div class="dtl">
                    <span style="color:#777;">الموظف: {{ $request->employee?->item?->name ?? $request->employee?->name }}</span>
                </div>
            @endif
            @if($request->customer)
                <div class="dtl">
                    <span>العميل: {{ $request->customer?->item?->name ?? '-' }}</span>
                </div>
            @endif
            @if($request->salesRout)
                <div class="dtl">
                    <span>الطريق: {{ $request->salesRout?->name ?? '-' }}</span>
                </div>
            @endif
            <div class="dtl">
                المستخدم: {{ $request->user?->name ?? '-' }}
            </div>
            <div class="dtl">
                <strong>التاريخ: {{ $request->created_at->format('Y-m-d H:i') }}</strong>
            </div>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th>الكود</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($request->items as $item)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $item->product?->item?->name ?? '-' }}</td>
                            <td>{{ $item->product?->code ?? '-' }}</td>
                            <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                            <td>{{ $item->count }}</td>
                            <td>{{ $request->description ?? '-' }}</td>
                            <td>{{ ($request->status=='pending'?'غير معتمد' :'معتمد' )?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection
