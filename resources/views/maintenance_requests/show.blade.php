{{-- resources/views/maintenance_requests/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تفاصيل طلب الصيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="show-wrapper">
        <div class="show-card">


            <div class="show-details">
                <div class="detail-row">
                    <span class="label">رقم الطلب:</span>
                    <span class="value">{{ $maintenanceRequest->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">المستخدم:</span>
                    <span class="value">{{ $maintenanceRequest->user->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">الأصل:</span>
                    <span class="value">{{ $maintenanceRequest->asset?->item?->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">مقدم الطلب:</span>
                    <span class="value">{{ $maintenanceRequest->employee?->name ?? 'غير محدد' }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">المشكلة:</span>
                    <span class="value">{{ $maintenanceRequest->issue_text }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">نوع الطلب:</span>
                    <span class="value">{{ $maintenanceRequest->issueType?->name ?? 'غير محدد' }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">البلاغ المرتبط:</span>
                    <span class="value">{{ $maintenanceRequest->report?->id ?? 'غير مرتبط' }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">تاريخ الإنشاء:</span>
                    <span class="value">{{ $maintenanceRequest->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <div class="show-footer">
                <a href="{{ route('maintenance_requests.index') }}" class="btn btn-primary">
                    رجوع للقائمة
                </a>
                <a href="{{ route('maintenance_requests.edit', $maintenanceRequest->id) }}" class="btn btn-worn">
                    تعديل
                </a>
                <form action="{{ route('maintenance_requests.destroy', $maintenanceRequest->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
                <a href="{{ route('maintenance_solutions.create', $maintenanceRequest->id) }}" class="btn btn-secondary">حل</a>

            </div>
        </div>
    </div>
</x-app-layout>

