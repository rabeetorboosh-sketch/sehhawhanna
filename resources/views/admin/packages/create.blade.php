<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                 اضافة حزمة تراخيص
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/permissions.css') }}">

<div class="container-form">





    <form method="POST" action="{{ route('packages.store') }}" class="smart-form">
        @csrf
        <div class="permission-header">
            <div class="row-3">
                <div class="select-all form-group">
                    <input type="checkbox" id="selectAll" class="checkbox">
                    <label for="selectAll">تحديد الكل</label>
                </div>
                <div class="form-group">
                    <input type="text" class="search-box" placeholder="ابحث عن صلاحية...">
                </div>
            </div>

        </div>
        <div class="row-2">

            <div class="form-group">
                <label>  اسم الحزمة</label>
                <input type="text"  name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>  الوصف </label>
                <textarea name="description"  >{{ old('description') }}</textarea>

            </div>
        </div>
{{--        <h3>تهيئة النظام</h3>--}}
{{--        <ul class="departments">--}}
{{--            <li>--}}
{{--                <strong>اللغة</strong> <input type="checkbox" class="checkbox parent" name="system[language]" value="1"  >--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <strong>الإعدادات العامة</strong> <input type="checkbox" class="checkbox parent" name="system[settings]" value="1"  >--}}
{{--            </li>--}}
{{--        </ul>--}}

        <!-- المدخلات العامة -->
        <h3>المدخلات العامة</h3>
        <ul class="departments">
            <li>
                <strong>المستخدمون</strong> <input type="checkbox" class="checkbox parent" name="general[users]" value="1"  >
                <ul>
                    <li><strong>إضافة</strong> <input type="checkbox" name="general[users][create]" value="1" class="checkbox" ></li>
                    <li><strong>عرض</strong> <input type="checkbox" name="general[users][view]" value="1" class="checkbox"></li>
                    <li><strong>تعديل</strong> <input type="checkbox" name="general[users][edit]" value="1" class="checkbox"></li>
                    <li><strong>حذف</strong> <input type="checkbox" name="general[users][delete]" value="1" class="checkbox"></li>
                </ul>
            </li>
            <li>
                <strong>الفروع</strong> <input type="checkbox" class="checkbox parent" name="general[branches]" value="1"  >
                <ul>
                    <li><strong>إضافة</strong> <input type="checkbox" name="general[branches][create]" value="1" class="checkbox"></li>
                    <li><strong>عرض</strong> <input type="checkbox" name="general[branches][view]" value="1" class="checkbox"></li>
                    <li><strong>تعديل</strong> <input type="checkbox" name="general[branches][edit]" value="1" class="checkbox"></li>
                    <li><strong>حذف</strong> <input type="checkbox" name="general[branches][delete]" value="1" class="checkbox"></li>
                </ul>
            </li>
            <li>
                <strong>أنواع المشاكل</strong> <input type="checkbox" class="checkbox parent" name="general[issues]" value="1"   >
                <ul>
                    <li><strong>إضافة </strong> <input type="checkbox" name="general[issues][create]" value="1" class="checkbox"></li>
                    <li><strong>عرض</strong> <input type="checkbox" name="general[issues][view]" value="1" class="checkbox"></li>
                    <li><strong>تعديل</strong> <input type="checkbox" name="general[issues][edit]" value="1" class="checkbox"></li>
                    <li><strong>حذف</strong> <input type="checkbox" name="general[issues][delete]" value="1" class="checkbox"></li>
                </ul>
            </li>
            <li>
                <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="general[control_units]" value="1"  >
                <ul>
                    <li><strong>إضافة </strong> <input type="checkbox" name="general[control_units][create]" value="1" class="checkbox"></li>
                    <li><strong>عرض</strong> <input type="checkbox" name="general[control_units][view]" value="1" class="checkbox"></li>
                    <li><strong>تعديل</strong> <input type="checkbox" name="general[control_units][edit]" value="1" class="checkbox"></li>
                    <li><strong>حذف</strong> <input type="checkbox" name="general[control_units][delete]" value="1" class="checkbox"></li>
                </ul>
            </li>
        </ul>

        <!-- المخزون (موجود أصلاً) -->
        <h3>رقابة المخزون</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="1[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>الأصناف</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][products]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][products][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][products][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][products][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][products][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الوحدات</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][units]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][units][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][units][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][units][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][units][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المخازن</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][stores]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][stores][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][stores][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][stores][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][stores][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][controlUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="1[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="1[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>التوالف</strong> <input type="checkbox" class="checkbox parent" name="1[operations][exp]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][exp][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[operations][exp][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][exp][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[operations][exp][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المرتجعات</strong> <input type="checkbox" class="checkbox parent" name="1[operations][ret]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][ret][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[operations][ret][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][ret][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[operations][ret][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>التحميلات</strong> <input type="checkbox" class="checkbox parent" name="1[operations][load]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][load][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="1[operations][load][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][load][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="1[operations][load][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <h3>رقابة الانتاج</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="10[insertions]" value="1"  >
                <ul>

                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="10[insertions][controlUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="10[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="10[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="10[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="10[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="10[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="10[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="10[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="10[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="10[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="10[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="10[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="10[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="10[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="10[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="10[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="10[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="10[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="10[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="10[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="10[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>


        <h3>رقابة الأصول</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="2[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>الأصول</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][assets]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][assets][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][assets][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][assets][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][assets][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][assets]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="2[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="2[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="2[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>طلبات الصيانة</strong> <input type="checkbox" class="checkbox parent" name="2[operations][maintenance_request]" value="1"  >
                        <ul>
                            <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][maintenance_request][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[operations][maintenance_request][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][maintenance_request][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[operations][maintenance_request][delete]" value="1" class="checkbox"></li>
                            <li><strong>اعتماد</strong> <input type="checkbox" name="2[operations][maintenance_request][approve]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>عمليات الصيانة</strong> <input type="checkbox" class="checkbox parent" name="2[operations][maintenance]" value="1"  >
                        <ul>
                            <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][maintenance][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[operations][maintenance][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][maintenance][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[operations][maintenance][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>نقل الأصول</strong> <input type="checkbox" class="checkbox parent" name="2[operations][movements]" value="1"  >
                        <ul>
                            <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][movements][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="2[operations][movements][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][movements][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="2[operations][movements][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- العملاء (موجود أصلاً) -->
        <h3>رقابة العملاء</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="8[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>العملاء</strong> <input type="checkbox" class="checkbox parent" name="8[insertions][customers]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][customers][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][customers][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][customers][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][customers][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الخطوط</strong> <input type="checkbox" class="checkbox parent" name="8[insertions][customers]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][sales_routs][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][sales_routs][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][sales_routs][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][sales_routs][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="8[insertions][controlUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="8[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="8[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="8[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="8[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="8[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الإشراف</strong> <input type="checkbox" class="checkbox parent" name="8[operations][supervises]" value="1" >
                        <ul>
                            <li><strong>إضافة</strong> <input type="checkbox" name="8[operations][supervises][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[operations][supervises][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[operations][supervises][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[operations][supervises][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>طلبات المندوبين </strong> <input type="checkbox" class="checkbox parent" name="8[operations][customersRequests]" value="1" >
                        <ul>
                            <li><strong>إضافة</strong> <input type="checkbox" name="8[operations][customersRequests][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="8[operations][customersRequests][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="8[operations][customersRequests][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="8[operations][customersRequests][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- رقابة العمالة -->
        <h3>رقابة العمالة</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="4[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>الموظفين</strong> <input type="checkbox" class="checkbox parent" name="4[insertions][employees]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][employees][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][employees][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][employees][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][employees][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>انواع الموظفين</strong> <input type="checkbox" class="checkbox parent" name="4[insertions][employeesTypes]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][employeesTypes][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][employeesTypes][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][employeesTypes][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][employeesTypes][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong> وحدات التسكين   </strong> <input type="checkbox" class="checkbox parent" name="4[insertions][housing_units]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][housing_units][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][housing_units][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][housing_units][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][housing_units][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>وحدات  التقييم </strong> <input type="checkbox" class="checkbox parent" name="4[insertions][ratingUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][ratingUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][ratingUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][ratingUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][ratingUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="4[insertions][controlUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="4[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="4[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="4[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="4[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>التقييمات</strong> <input type="checkbox" class="checkbox parent" name="4[operations][ratings]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][ratings][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[operations][ratings][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][ratings][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[operations][ratings][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>عمليات التسكين </strong> <input type="checkbox" class="checkbox parent" name="4[operations][housing_assignments]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][housing_assignments][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[operations][housing_assignments][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][housing_assignments][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[operations][housing_assignments][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الرقابة اليومية</strong> <input type="checkbox" class="checkbox parent" name="4[operations][monitoring]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][monitoring][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[operations][monitoring][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][monitoring][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[operations][monitoring][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المهام</strong> <input type="checkbox" class="checkbox parent" name="4[operations][tasks]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][tasks][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="4[operations][tasks][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][tasks][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="4[operations][tasks][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- رقابة الموردين -->
        <h3>رقابة الموردين</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="9[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>الموردين</strong> <input type="checkbox" class="checkbox parent" name="9[insertions][suppliers]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][suppliers][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][suppliers][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][suppliers][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][suppliers][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="9[insertions][controlUnits]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][controlUnits][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][controlUnits][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][controlUnits][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][controlUnits][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="9[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="9[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="9[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="9[operations][reports]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][reports][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[operations][reports][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][reports][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[operations][reports][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الرقابة اليومية</strong> <input type="checkbox" class="checkbox parent" name="9[operations][monitoring]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][monitoring][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[operations][monitoring][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][monitoring][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[operations][monitoring][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المهام</strong> <input type="checkbox" class="checkbox parent" name="9[operations][tasks]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][tasks][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="9[operations][tasks][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][tasks][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="9[operations][tasks][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- المهام -->
        <h3>المهام</h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="5[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>المهام</strong> <input type="checkbox" class="checkbox parent" name="5[insertions][tasks]" value="1"  >
                        <ul>
                            <li><strong>إضافة  </strong> <input type="checkbox" name="5[insertions][tasks][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="5[insertions][tasks][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="5[insertions][tasks][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="5[insertions][tasks][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="5[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>إسنادات المهام</strong> <input type="checkbox" class="checkbox parent" name="5[operations][assignments]" value="1"  >
                        <ul>
                            <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][assignments][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="5[operations][assignments][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][assignments][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="5[operations][assignments][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>استلامات المهام</strong> <input type="checkbox" class="checkbox parent" name="5[operations][receipts]" value="1"  >
                        <ul>
                            <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][receipts][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="5[operations][receipts][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][receipts][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="5[operations][receipts][delete]" value="1" class="checkbox"></li>
                            <li><strong>اعتماد وتقييم</strong> <input type="checkbox" name="5[operations][receipts][approve]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong> المهام اليومية</strong> <input type="checkbox" class="checkbox parent" name="5[operations][myTask]" value="1"  >
                        <ul>
                            <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][myTask][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="5[operations][myTask][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][myTask][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="5[operations][myTask][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- الرقابة اليومية -->
        <h3>الرقابة اليومية</h3>
        <ul class="departments">
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="daily_monitoring[daily_monitoring]" value="1"  >
                <ul>
                    <li>
                        <strong>التقارير اليومية</strong> <input type="checkbox" class="checkbox parent" name="daily_monitoring[daily_monitoring]" value="1"  >
                        <ul>
                            <li><strong>إضافة  </strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <h3> رقابة المشتريات  </h3>
        <ul class="departments">
            <li>
                <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="pur[insertions]" value="1"  >
                <ul>
                    <li>
                        <strong>الوحدات</strong> <input type="checkbox" class="checkbox parent" name="pur[insertions][units]" value="1"   >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[insertions][units][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[insertions][units][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[insertions][units][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[insertions][units][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>الأصناف</strong> <input type="checkbox" class="checkbox parent" name="pur[insertions][products]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[insertions][products][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[insertions][products][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[insertions][products][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[insertions][products][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>

                    <li>
                        <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="pur[insertions][main_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[insertions][main_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[insertions][main_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[insertions][main_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[insertions][main_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="pur[insertions][sub_groups]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[insertions][sub_groups][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[insertions][sub_groups][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[insertions][sub_groups][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[insertions][sub_groups][delete]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="pur[operations]" value="1"  >
                <ul>
                    <li>
                        <strong>الطلبات</strong> <input type="checkbox" class="checkbox parent" name="pur[operations][requests]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[operations][requests][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[operations][requests][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[operations][requests][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[operations][requests][delete]" value="1" class="checkbox"></li>
                            <li><strong>اعتماد</strong> <input type="checkbox" name="pur[operations][requests][approve]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>المشتريات</strong> <input type="checkbox" class="checkbox parent" name="pur[operations][purchase]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pur[operations][purchase][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pur[operations][purchase][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pur[operations][purchase][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pur[operations][purchase][delete]" value="1" class="checkbox"></li>
                            <li><strong>اعتماد</strong> <input type="checkbox" name="pur[operations][purchase][approve]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                    <li>
                        <strong>الاستلامات</strong> <input type="checkbox" class="checkbox parent" name="pur[operations][intake]" value="1"  >
                        <ul>
                            <li><strong>إضافة </strong> <input type="checkbox" name="pu[operations][intake][create]" value="1" class="checkbox"></li>
                            <li><strong>عرض</strong> <input type="checkbox" name="pu[operations][intake][view]" value="1" class="checkbox"></li>
                            <li><strong>تعديل</strong> <input type="checkbox" name="pu[operations][intake][edit]" value="1" class="checkbox"></li>
                            <li><strong>حذف</strong> <input type="checkbox" name="pu[operations][intake][delete]" value="1" class="checkbox"></li>
                            <li><strong>اعتماد</strong> <input type="checkbox" name="pu[operations][intake][approve]" value="1" class="checkbox"></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>


        <div class="actions">
            <a href="{{ route('permissions.index') }}" class="btn btn-cancel">
                <i class="fas fa-times" style="margin-left: 8px;"></i>
                إلغاء
            </a>

            <button type="submit" class="btn btn-save">
                <i class="fas fa-save" style="margin-left: 8px;"></i>
                حفظ
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // إضافة أزرار التوسيع والطي
        document.querySelectorAll('ul.departments li strong').forEach(function (strong) {
            const li = strong.parentElement;
            if (li.querySelector('ul')) {
                const toggle = document.createElement('span');
                toggle.classList.add('dropdown-toggle');
                toggle.innerHTML = '&#9654;';
                strong.prepend(toggle);

                strong.addEventListener('click', function (e) {
                    e.stopPropagation();
                    li.classList.toggle('open');
                });
            }
        });

        // تحديد/إلغاء تحديد الكل
        document.getElementById('selectAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[type=checkbox]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // تفعيل خاصية تحديد/إلغاء تحديد العناصر الفرعية عند تحديد العنصر الرئيسي
        document.querySelectorAll('.parent').forEach(function (parentBox) {
            parentBox.addEventListener('change', function () {
                const li = this.closest('li');
                if (li) {
                    li.querySelectorAll('input[type=checkbox]').forEach(chk => {
                        chk.checked = this.checked;
                    });
                }
            });
        });

        // وظيفة البحث
        const searchBox = document.querySelector('.search-box');
        searchBox.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('ul.departments li');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                    // توسيع العناصر التي تحتوي على النص المطلوب
                    let parent = item.parentElement.closest('li');
                    while (parent) {
                        parent.classList.add('open');
                        parent = parent.parentElement.closest('li');
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
<script src="{{ asset('js/form.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</x-app-layout>
