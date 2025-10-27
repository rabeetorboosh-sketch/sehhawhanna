{{-- resources/views/admin/asset/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الصنف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assetShow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">


                <h3 class="section-title">بيانات الصنف</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الصنف</span>
                        <span class="info-content">{{ $product->item->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الكود</span>
                        <span class="info-content">{{ $product->code ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الوصف</span>
                        <span class="info-content">{{ $product->description ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الوحدات</span>
                        <div >
                            <table class="inside-table" >
                                <thead>
                                <tr>

                                    <th>الوحدة </th>
                                    <th>العبوة </th>
                                    <th>  رئيسية </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->item?->units as $unit)
                                        <tr>
                                            <td>{{ $unit->unit?->name ?? '—' }}</td>
                                            <td>{{ $unit->package ?? '—' }}</td>
                                            <td> <input type="checkbox" @if($unit->is_main??0==1) checked @endif disabled>   </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                     </div>

                </div>

                    <span class="info-title">الكميات في كل مخزن</span>
                    <div class="table-wrap desktop-view">
                        <div class="table">
                            <table class="table sortable">
                            <thead>
                            <tr>

                                <th>المخزن </th>
                                <th>الوحدة </th>
                                <th>  الكمية </th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $quantities as $quantity)
                                @if($quantity->count>=0)
                                    <tr>
                                        <td>{{ $quantity->store_name ?? '—' }}</td>
                                        <td>{{ $quantity->unit ?? '—' }}</td>
                                        <td>{{ $quantity->count ?? '—' }}</td>
                                    </tr>
                                @endif

                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
                {{-- حركات الأصل --}}
                <div class="mobile-view">
                    @foreach($quantities as $quantity)
                            <div class="card">
                                <p><strong> المخزن:</strong>
                                    {{ $quantity->store_name ?? '-' }}
                                </p>
                                <p><strong>الوحدة: </strong>
                                    {{ $quantity->unit ?? '-' }}
                                </p>
                                <p><strong> الكمية:</strong>
                                    {{ $quantity->count?? '-' }}
                                </p>

                            </div>

                    @endforeach
                </div>
                    <h3 class="section-title">حركات الصنف</h3>
                    @if($transactions->isNotEmpty())
                        {{-- عرض كجداول على الكمبيوتر --}}
                        <div class="table-wrap desktop-view">
                            <div class="table-scroll">
                                <table class="table sortable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>من مخزن</th>
                                        <th>إلى مخزن</th>
                                        <th>نوع العملية</th>
                                        <th>الموظف</th>
                                        <th>المستخدم</th>
                                        <th>الوحدة</th>
                                        <th>الكمية</th>
                                        <th>التاريخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $transaction)
                                        @foreach($transaction->items as $item)

                                            <tr>
                                                <td>{{ $transaction->id }}</td>
                                                <td>{{ $transaction->FromStore?->name ?? '-' }}</td>
                                                <td>{{ $transaction->ToStore?->name ?? '-' }}</td>
                                                <td>{{ $transaction->movement?->name ?? '-' }}</td>
                                                <td>{{ $transaction->employee?->item?->name ?? ''}}</td>
                                                <td>{{ $transaction->user?->name ?? '-' }}</td>
                                                <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                                                <td>{{ $item->count }}</td>
                                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- عرض كبطاقات على الهاتف --}}
                        <div class="mobile-view">
                            @foreach($transactions as $transaction)
                                @foreach($transaction->items as $item)
                                <div class="card">
                                    <p><strong>من مخزن:</strong>
                                        {{ $transaction->FromStore?->name ?? '-' }}
                                    </p>
                                    <p><strong>إلى مخزن:</strong>
                                        {{ $transaction->ToStore?->name ?? '-' }}
                                    </p>
                                    <p><strong>نوع العملية:</strong>
                                        {{ $transaction->movement?->name ?? '-' }}
                                    </p>
                                    <p><strong>الموظف:</strong>
                                        {{ $transaction->employee?->item?->name ?? ''}}
                                    </p>
                                    <p><strong>المستخدم:</strong>
                                        {{ $transaction->user?->name ?? '-' }}
                                    </p>
                                    <p><strong>الوحدة:</strong>
                                        {{ $item->unit?->unit?->name ?? '-' }}
                                    </p>
                                    <p><strong>الكمية:</strong>
                                        {{ $item->count }}
                                    </p>
                                    <p><strong>التاريخ:</strong>
                                        {{ $transaction->created_at->format('Y-m-d H:i') }}
                                    </p>
                                 </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <p>لا توجد حركات مسجلة لهذا الصنف.</p>
                    @endif

                <div class="mt-6">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>

            </div>
        </div>
    </div>
    <script  src="{{asset('js/table.js')}}"></script>
</x-app-layout>
