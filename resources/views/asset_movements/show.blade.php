{{-- resources/views/asset_movements/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض حركة الأصل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات الحركة</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الأصل</span>
                        <span class="info-content">{{ $movement->asset->item->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">من</span>
                        <span class="info-content">
                            @if($movement->from_item_type==4)
                                {{ $movement->fromEmployee?->item?->name ?? 'غير محدد' }}
                            @elseif($movement->from_item_type==8)
                                {{ $movement->fromCustomer?->item?->name ?? 'غير محدد' }}
                            @elseif($movement->from_item_type==9)
                                {{ $movement->fromSupplier?->item?->name ?? 'غير محدد' }}
                            @else
                                —
                            @endif
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">إلى</span>
                        <span class="info-content">
                            @if($movement->to_item_type==4)
                                {{ $movement->toEmployee?->item?->name ?? 'غير محدد' }}
                            @elseif($movement->to_item_type==8)
                                {{ $movement->toCustomer?->item?->name ?? 'غير محدد' }}
                            @elseif($movement->to_item_type==9)
                                {{ $movement->toSupplier?->item?->name ?? 'غير محدد' }}
                            @else
                                —
                            @endif
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">تاريخ ووقت النقل</span>
                        <span class="info-content">{{ $movement->movement_datetime }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">السبب</span>
                        <span class="info-content">{{ $movement->reason ?? '—' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">حالة الأصل</span>
                        <span class="info-content">{{ $movement->asset_status ?? '—' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">وجهة النقل</span>
                        <span class="info-content">{{ $movement->movement_destination ?? '—' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المستخدم</span>
                        <span class="info-content">{{ $movement->user->name ?? 'غير محدد' }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('asset_movements.index') }}" class="btn-back">العودة للحركات</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
