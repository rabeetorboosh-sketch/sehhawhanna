<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض تفاصيل المخالفة رقم #{{ $violation->id }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات المخالفة الأساسية --}}
                <h3 class="section-title">بيانات المخالفة</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الموظف المعني</span>
                        <span class="info-content">{{ $violation->employee->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المشرف المسؤول</span>
                        <span class="info-content">{{ $violation->creator->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">نوع المخالفة</span>
                        <span class="info-content">{{ $violation->violations_type }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">تاريخ التسجيل</span>
                        <span class="info-content">{{ $violation->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>

                {{-- تفاصيل الإرسال --}}
                <h3 class="section-title mt-6">تفاصيل الإرسال</h3>
                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-right border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border text-center">الجهة المرسل إليها</th>
                            <th class="py-3 px-4 border text-center">الحالة</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($violation->sent_to))
                            @foreach($violation->sent_to as $target)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border text-center">{{ strtoupper($target) }}</td>
                                    <td class="py-2 px-4 border text-center">
                                        <span class="status completed">تم الإرسال</span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="py-4 text-center text-gray-500">لا يوجد جهات محددة</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                {{-- الملاحظات --}}
                <h3 class="section-title mt-6">الملاحظات والوصف</h3>
                <div class="info-grid" style="grid-template-columns: 1fr;">
                    <div class="info-card" style="min-height: 100px;">
                        <span class="info-title">وصف المخالفة</span>
                        <p class="info-content" style="white-space: pre-wrap; margin-top: 10px; line-height: 1.6;">
                            {{ $violation->note ?? 'لا توجد ملاحظات إضافية' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('violations.index') }}" class="btn btn-primary">عودة للقائمة</a>

                    @if(Auth::user()->permissions('violations')?->can_update == 1)
                        <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-worn" style="background-color: #f6ad55; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">تعديل المخالفة</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
