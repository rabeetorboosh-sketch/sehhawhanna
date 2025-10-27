@extends('reports.assetsMovements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        /* ========== تنسيق التقرير الخارجي (باستثناء الجدول) ========== */

        /* اسم العملية (الحركة) */
        .operation-name {
            font-size: 1.4rem;
            font-weight: bold;
            color: #525255; /* أزرق ملكي */
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #535a5c;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
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
            padding: 3px;
            border-radius: 5px;
        }

        /* إضافة فراغ بين الجداول */
        .table-wrap {
            margin-bottom: 30px;
        }

        /* تأثير بسيط عند المرور */
        .operation-name:hover, .store-name:hover {
            transform: translateX(1px);
            transition: 0.2s ease;

        }

    </style>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الأصل</th>
                    <th>من </th>
                    <th>إلى </th>
                    <th>تاريخ ووقت النقل</th>
                    <th>السبب</th>
                    <th>حالة الأصل</th>
                    <th>وجهة النقل</th>
                    <th>المستخدم</th>

                </tr>
                </thead>
                <tbody>
                @foreach($movements as $movement)
                    <tr>
                        <td>{{ $movement->id }}</td>
                        <td>{{ $movement->asset->item->name }}</td>
                        <td>
                            @if($movement->from_item_type==4)
                                {{ $movement->fromEmployee?->item?->name }}
                            @elseif($movement->from_item_type==8)
                                {{ $movement->fromCustomer?->item?->name }}
                            @elseif($movement->from_item_type==9)
                                {{ $movement->fromSupplier?->item?->name }}
                            @endif
                        </td>
                        <td>
                            @if($movement->to_item_type==4)
                                {{ $movement->toEmployee?->item?->name }}
                            @elseif($movement->to_item_type==8)
                                {{ $movement->toCustomer?->item?->name }}
                            @elseif($movement->to_item_type==9)
                                {{ $movement->toSupplier?->item?->name }}
                            @endif
                        </td>

                        <td>{{ $movement->movement_datetime }}</td>
                        <td>{{ $movement->reason }}</td>
                        <td>
                            {{ $movement->asset_status?? ''}}
                        </td>
                        <td>{{ $movement->movement_destination }}</td>
                        <td>{{ $movement->user->name ?? 'غير محدد' }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>


        </div>
    </div>
@endsection
