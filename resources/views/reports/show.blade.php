<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            عرض التقرير
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report-show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات التقرير</h3>
                <table class="report-table">
                    <tr>
                        <th>نوع البلاغ</th>
                        <td >{{ $report->reportType->name?? '' }} <span style="background:{{ $report->reportType->color?? 'gray' }} ;width: 24px;display: inline-block;height: 20px;  position: relative;top: 4px; left: 0;margin-right: 10px;border-radius:50%;" >    </span></td>

                    </tr>
                    <tr>
                        <th>القسم</th>
                        <td>{{ $report->department->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>{{ $report->status == 0 ? 'جديد' : 'تم المعالجة' }}</td>
                    </tr>
                    <tr>
                        <th>المستخدم</th>
                        <td>{{ $report->user->name }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء</th>
                        <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>

                <h3 class="section-title">بنود التقرير</h3>
                @foreach($report->items as $item)

                    <div class="item-card">
                        <h4>البند: {{ $item->item->name ?? 'غير محدد' }}</h4>
                        <p><strong>الوحدة الرقابية:</strong> {{ $item->controlUnit->name ?? $item->user_control_unit ?? 'يدوي' }}</p>
                        <p><strong>المتسبب:</strong> {{ $item->causer ? $item->causer->item->name : 'لا يوجد' }}</p>
                        <p><strong>الوصف:</strong> {{ $item->issue_description }}</p>
                        <p><strong>حالة الرد:</strong> {{ $item->response_status ? 'تم الرد' : 'قيد الانتظار' }}</p>

                        @if($item->media && $item->media->isNotEmpty())
                            <div class="media-grid">
                                @foreach($item->media as $media)

                                    <a href="{{ asset('storage/uploads/report_items/fH51WO11I9tyo7p7Hcim4UaBhyZeStS6UcH45jVn.png') }}" target="_blank">
                                        <img src="{{ asset($media->url)}}" alt="صورة البند" class="media-thumb">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="mt-6">
                    <a href="{{ route('reports.index') }}" class="btn-back">العودة للتقارير</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
