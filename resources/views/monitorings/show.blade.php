<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الرقابة اليومية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report-show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات الرقابة اليومية</h3>
                <table class="report-table">
                    <tr>
                        <th>اليوم</th>
                        <td>{{ $monitoring->day }}</td>
                    </tr>
                    <tr>
                        <th>المستخدم</th>
                        <td>{{ $monitoring->user->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>عدد الوحدات الرقابية</th>
                        <td>{{ $monitoring->items->pluck('control_unit_id')->unique()->count() }}</td>
                    </tr>
                    <tr>
                        <th>عدد البنود</th>
                        <td>{{ $monitoring->items->count() }}</td>
                    </tr>
                    <tr>
                        <th>الحالة العامة</th>
                        <td>
                            @if($monitoring->items->every(fn($item) => $item->is_correct))
                                <span class="text-green-600">سليم</span>
                            @elseif($monitoring->items->every(fn($item) => !$item->is_correct))
                                <span class="text-red-600">مشكلة</span>
                            @else
                                <span class="text-yellow-600">مختلط</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <h3 class="section-title">بنود الرقابة</h3>

                @php
                    // تجميع البنود حسب الوحدة الرقابية
                    $groupedItems = $monitoring->items->groupBy('control_unit_id');
                @endphp
<div class="units-container">


                @foreach($groupedItems as $controlUnitId => $items)
                    @php
                        $controlUnit = $items->first()->controlUnit ?? null;
                        $total = $items->count();
                        $correct = $items->where('is_correct', true)->count();
                        $incorrect = $total - $correct;
                    @endphp

                    <div class="unit-section">


                        <div class="item-card">
                                <h4 class="unit-title text-center text-lg font-bold mb-3 border-b pb-2">
                                      {{ $controlUnit->name ?? 'يدوي' }}
                                </h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    عدد البنود: {{ $total }}
                                </p>

                            @foreach($items as $item)

                                    <h5>البند: {{ $item->item->name ?? 'غير محدد' }}</h5>

                            @endforeach
                            <p><strong>المتسبب:</strong> {{ $item->causer ? $item->causer->item->name : 'لا يوجد' }}</p>
                            <p><strong>المشكلة :</strong> {{ $item->description ?? 'لا يوجد وصف' }}</p>
                            <p><strong>الحالة:</strong>
                                @if($item->is_correct)
                                    <span class="status-span status-correct">سليم</span>
                                @else
                                    <span class="status-span status-incorrect">يوجد مشكلة</span>
                                @endif
                            </p>


                        @if(isset($item->media) && $item->media->isNotEmpty())
                                <div class="media-grid">
                                    @foreach($item->media as $media)
                                        <a href="{{ asset($media->url) }}" target="_blank">
                                            <img src="{{ asset($media->url) }}" alt="صورة البند" class="media-thumb">
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
</div>
                <div class="mt-6">
                    <a href="{{ route('monitoring.index') }}" class="btn-back">العودة للرقابات اليومية</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
