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
                @foreach($monitoring->items as $item)
                    <div class="item-card">
                        <h4>البند: {{ $item->item->name ?? 'غير محدد' }}</h4>
                        <p><strong>الوحدة الرقابية:</strong> {{ $item->controlUnit->name ?? 'يدوي' }}</p>
                        <p><strong>المتسبب:</strong> {{ $item->causer ? $item->causer->item->name : 'لا يوجد' }}</p>
                        <p><strong>الوصف:</strong> {{ $item->description ?? 'لا يوجد وصف' }}</p>
                        <p><strong>الحالة:</strong> {{ $item->is_correct ? 'سليم' : 'يوجد مشكلة' }}</p>

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
                @endforeach

                <div class="mt-6">
                    <a href="{{ route('monitoring.index') }}" class="btn-back">العودة للرقابات اليومية</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
