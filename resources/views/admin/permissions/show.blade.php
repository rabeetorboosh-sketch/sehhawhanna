<x-app-layout>

    @php

    $operationTranslations = [
    'insertions' => 'المدخلات',
    'operations' => 'العمليات',
    'reports'    => 'التقارير',
    'settings'   => 'الإعدادات',
    'daily_monitoring'   => 'الرقابة اليومية',
];

// ترجمة الموديلات
$modelTranslations = [
    'products'      => 'الأصناف',
    'units'         => 'الوحدات',
    'stores'        => 'المخازن',
    'controlUnits'  => 'وحدات الرقابة',
    'customers'     => 'العملاء',
    'suppliers'     => 'الموردين',
    'employees'     => 'الموظفين',
    'tasks'      => 'المهام',
    'departments'   => 'الأقسام',
    'groups'        => 'المجموعات',
    'users'         => 'المستخدمين',
    'reports'         => 'البلاغات',
    'permissions'   => 'الصلاحيات',
    'packages'      => 'الحزم',
    'maintenance_request'      => 'طلبات الصيانة',
    'maintenance'      => ' عمليات الصيانة',
    'movements'      => 'نقل الاصول',
    'monitoring'      => ' الرقابة اليومية ',
    'assignments'      => '  اسناد المهام ',
    'supervises'      => '  الاشراف ',
    'receipts'      => '  استلام المهام ',
    'branches'      => ' الفروع',
    'issues'      => ' انواع المشاكل',
    'control_units'      => ' وحدات الرقابة',
    'main_groups'   => 'المجموعات الرئيسية',
    'assets'   => ' الاصول',
    'sub_groups'   => 'المجموعات الفرعية',
    'daily_monitoring'   => 'الرقابة اليومية',
    'operations'   => 'العمليات',
    'myTask'   => 'مهامي اليومية',
    'exp'   => 'التوالف',
    'ret'   => '  المرتجعات',
    'load'   => '  التحميل',
    'ratingUnits'   => ' وحدات التقييم',
    'ratings'   => '   التقييم',
    'sales_routs'   => '   الخطوط',
    'customersRequests'   => '   طلبات العملاء',
    'employeesTypes'   => '      انواع الموظفين',
    'housing_units'   => '         وحدات التسكين',
    'housing_assignments'   => '         عمليات التسكين',
    'request'   => '    طلبات المشتريات',
    'purchase'   => '     المشتريات',
    'intake'   => '   استلامات  المشتريات',
];
@endphp


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الحزمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assetShow.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات الحزمة --}}
                <h3 class="section-title">بيانات الحزمة</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">اسم المستخدم</span>
                        <span class="info-content">{{ $user->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">اسم الحزمة</span>
                        <span class="info-content">{{ $user->packages?->first()->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الوصف</span>
                        <span class="info-content">{{ $user->packages?->first()->description ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">عدد النماذج</span>
                        <span class="info-content">{{ $user->templates->count() }}</span>
                    </div>
                </div>

                {{-- جدول الصلاحيات --}}
                <h3 class="section-title mt-6">الصلاحيات لكل قسم</h3>

                @php
                    // ترجمة العمليات
                    $operationTranslations = [
                        'insertions' => 'المدخلات',
                        'operations' => 'العمليات',
                        'reports'    => 'التقارير',
                        'settings'   => 'الإعدادات',
                    ];

                    // ترتيب البيانات حسب القسم → العملية → الموديل
                    $grouped = [];
                    foreach($user->templates as $template) {
                        $parts = explode('-', $template->model);
                        if(count($parts) === 3) {
                            [$deptId, $operation, $subModel] = $parts;
                            $grouped[$deptId][$operation][$subModel] = $template;
                        }elseif (count($parts)   ===2){

                         [$deptId, $subModel] = $parts;
                          $grouped[$deptId]['مدخلات'][$subModel] = $template;

                        }
                    }
                @endphp

                @if(!empty($grouped))
                    @foreach($grouped as $deptId => $operations)
                        <div class="department-block">
                        <h4 class="mt-6 text-lg font-bold">
                            القسم: {{ $departments[$deptId] ?? "القسم رقم $deptId" }}
                        </h4>

                        @foreach($operations as $operation => $models)
                            <h5 class="mt-2 text-md font-semibold">
                                  {{ $operationTranslations[$operation] ?? $operation }}
                            </h5>

                            {{-- نسخة الكمبيوتر --}}
                            <div class="table-container desktop-view mb-4">
                                <table class="styled-table">
                                    <thead>
                                    <tr>
                                        <th>الموديل</th>
                                        <th>عرض</th>
                                        <th>إنشاء</th>
                                        <th>تعديل</th>
                                        <th>حذف</th>
                                        <th>اعتماد</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($models as $subModel => $template)
                                        <tr>
                                            <td>{{    $modelTranslations[$subModel]  }}</td>
                                            <td>{{ $template->can_show ? '✓' : '✗' }}</td>
                                            <td>{{ $template->can_create ? '✓' : '✗' }}</td>
                                            <td>{{ $template->can_update ? '✓' : '✗' }}</td>
                                            <td>{{ $template->can_delete ? '✓' : '✗' }}</td>
                                            <td>{{ $template->can_approve ? '✓' : '✗' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- نسخة الموبايل --}}
                            <div class="mobile-view mb-4">
                                @foreach($models as $subModel => $template)
                                    <div class="card mb-2">

                                        <p><strong>الموديل:</strong> {{$modelTranslations[$subModel]  ?? $subModel }}</p>
                                        <p><strong>عرض:</strong> {{ $template->can_show ? '✓' : '✗' }}</p>
                                        <p><strong>إنشاء:</strong> {{ $template->can_create ? '✓' : '✗' }}</p>
                                        <p><strong>تعديل:</strong> {{ $template->can_update ? '✓' : '✗' }}</p>
                                        <p><strong>حذف:</strong> {{ $template->can_delete ? '✓' : '✗' }}</p>
                                        <p><strong>اعتماد:</strong> {{ $template->can_approve ? '✓' : '✗' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        </div>
                    @endforeach
                @else
                    <p>لا توجد صلاحيات مسجلة لهذا الحزمة.</p>
                @endif

                <div class="mt-6">
                    <a href="{{ route('permissions.index') }}" class="btn-back">العودة للحزم</a>
                    <a href="{{ route('permissions.edit',$user->id ) }}" class="btn-show ml-2">تعديل الحزمة</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
