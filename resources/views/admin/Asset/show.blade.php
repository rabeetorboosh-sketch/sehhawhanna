{{-- resources/views/admin/asset/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الأصل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assetShow.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات الأصل --}}
                <h3 class="section-title">بيانات الأصل</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الأصل</span>
                        <span class="info-content">{{ $asset->item->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">رقم الأصل</span>
                        <span class="info-content">{{ $asset->id_number ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الوصف</span>
                        <span class="info-content">{{ $asset->description ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">تاريخ الاستخدام</span>
                        <span class="info-content">{{ $asset->usage_date ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">العمر الافتراضي</span>
                        <span class="info-content">     {{ $asset->lifetime ?? '—' }}/سنوات     </span>
                    </div>
                </div>

                {{-- حركات الأصل --}}
                @if(Auth::user()->permissions('2-operations-movements')?->can_show == 1)
                <h3 class="section-title">حركات الأصل</h3>
                @if($assetsMovements->isNotEmpty())
                    {{-- عرض كجداول على الكمبيوتر --}}
                    <div class="table-container desktop-view">
                        <table class="styled-table">
                            <thead>
                            <tr>
                                <th>من</th>
                                <th>إلى</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الوجهة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assetsMovements as $movement)
                                <tr>
                                    <td>     @if($movement->from_item_type==4)
                                            {{ $movement->fromEmployee?->item?->name }}
                                        @elseif($movement->from_item_type==8)
                                            {{ $movement->fromCustomer?->item?->name }}
                                        @elseif($movement->from_item_type==9)
                                            {{ $movement->fromSupplier?->item?->name }}
                                        @endif
                                    </td>
                                    <td> @if($movement->to_item_type==4)
                                            {{ $movement->toEmployee?->item?->name }}
                                        @elseif($movement->to_item_type==8)
                                            {{ $movement->toCustomer?->item?->name }}
                                        @elseif($movement->to_item_type==9)
                                            {{ $movement->toSupplier?->item?->name }}
                                        @endif</td>
                                    <td>{{ $movement->movement_datetime ?? '—' }}</td>
                                    <td>{{ $movement->asset_status ?? '—' }}</td>
                                    <td>{{ $movement->movement_destination ?? '—' }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- عرض كبطاقات على الهاتف --}}
                    <div class="mobile-view">
                        @foreach($assetsMovements as $movement)
                            <div class="card">
                                <p><strong>من:</strong>      @if($movement->from_item_type==4)
                                        {{ $movement->fromEmployee?->item?->name }}
                                    @elseif($movement->from_item_type==8)
                                        {{ $movement->fromCustomer?->item?->name }}
                                    @elseif($movement->from_item_type==9)
                                        {{ $movement->fromSupplier?->item?->name }}
                                    @endif
                                </p>
                                <p><strong>إلى:</strong>  @if($movement->to_item_type==4)
                                        {{ $movement->toEmployee?->item?->name }}
                                    @elseif($movement->to_item_type==8)
                                        {{ $movement->toCustomer?->item?->name }}
                                    @elseif($movement->to_item_type==9)
                                        {{ $movement->toSupplier?->item?->name }}
                                    @endif</p>
                                <p><strong>التاريخ:</strong> {{ $movement->movement_datetime ?? '—' }}</p>
                                <p><strong>الحالة:</strong> {{ $movement->asset_status ?? '—' }}</p>
                                <p><strong>الوجهة:</strong> {{ $movement->movement_destination ?? '—' }}</p>
                             </div>
                        @endforeach
                    </div>
                @else
                    <p>لا توجد حركات مسجلة لهذا الأصل.</p>
                @endif
                @endif
                @if(Auth::user()->permissions('2-operations-maintenance')?->can_show == 1)

                <h3 class="section-title">عمليات الصيانة</h3>
                @if($assetMaintenance->isNotEmpty())
                    {{-- عرض كجداول على الكمبيوتر --}}
                    <div class="table-container desktop-view">
                        <table class="styled-table">
                            <thead>
                            <tr>
                                <th>المشكلة</th>
                                <th>تكلفة الإصلاح</th>
                                <th>الضمان</th>
                                <th>إجراء</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assetMaintenance as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->MaintenanceRequest->issue_text ?? '—' }}</td>
                                    <td>{{ $maintenance->repair_cost ?? '—' }}</td>
                                    <td>
                                        @if($maintenance->has_warranty)
                                            <span class="status completed">{{ $maintenance->warranty_type ?? '' }}</span>
                                        @else
                                            <span class="status pending">بدون ضمان</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('maintenance_solutions.show', $maintenance->id) }}" class="btn-show">عرض</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- عرض كبطاقات على الهاتف --}}
                    <div class="mobile-view">
                        @foreach($assetMaintenance as $maintenance)
                            <div class="card">
                                <p><strong>المشكلة:</strong> {{ $maintenance->MaintenanceRequest->issue_text ?? '—' }}</p>
                                <p><strong>تكلفة الإصلاح:</strong> {{ $maintenance->repair_cost ?? '—' }}</p>
                                <p><strong>الضمان:</strong>
                                    @if($maintenance->has_warranty)
                                        {{ $maintenance->warranty_type ?? '' }}
                                    @else
                                        بدون ضمان
                                    @endif
                                </p>
                                <a href="{{ route('maintenance_solutions.show', $maintenance->id) }}" class="btn-show">عرض</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>لا توجد عمليات صيانة مسجلة لهذا الأصل.</p>
                @endif
                @endif
                <div class="mt-6">
                    <a href="{{ route('asset.index') }}" class="btn-back">العودة للأصول</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
