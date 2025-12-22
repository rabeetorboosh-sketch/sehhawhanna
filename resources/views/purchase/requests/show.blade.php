@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">عرض تفاصيل طلب الشراء</h1>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>رقم الطلب:</strong> {{ $purchaseRequest->id }}</p>
                <p><strong>التاريخ:</strong> {{ $purchaseRequest->created_at->format('d-m-Y h:i A') }}</p>
                <p><strong>ملاحظات:</strong> {{ $purchaseRequest->note }}</p>

            </div>
        </div>

        <h2>العناصر المطلوبة:</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>اسم الصنف</th>
                <th>الوحدة </th>
                <th>الكمية المطلوبة</th>
                <th> معتمد؟</th>
                <th>تحديث الحالة </th>
            </tr>
            </thead>
            <tbody>
            @foreach($purchaseRequest->requestItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item->name ?? 'غير متوفر' }}</td>
                    <td>{{ $item->unit->unit_name ?? 'غير متوفر' }}</td>
                    <td>{{ $item->request_count }}</td>
                    <td>{{ $item->is_confirmed ? '✅' : '❌' }}</td>
                    <td>
                        @if(Auth::user()->isAdmin())
                        <form method="POST" action="{{ route('purchase_requests.confirmItem', $item->id) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">

                                @if($item->is_confirmed==1)
                                    الغاء الاعتماد
                                @else
                                اعتماد
                                @endif

                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ route('purchase_requests.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        @if(Auth::user()->isAdmin() or Auth::user()->is_request()>1)
        <a href="{{ route('purchase_requests.edit', $purchaseRequest) }}" class="btn btn-primary">تعديل</a>
        @endif
    </div>
@endsection
