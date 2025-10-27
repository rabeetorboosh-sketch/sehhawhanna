<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل حزمة تراخيص
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/permissions.css') }}">

    <div class="container-form">
        <form method="POST" action="{{ route('packages.update', $package->id) }}" class="smart-form">
            @csrf
            @method('PUT')
            <div class="permission-header">
                <div class="select-all">
                    <input type="checkbox" id="selectAll" class="checkbox">
                    <label for="selectAll">تحديد الكل</label>
                </div>
                <input type="text" class="search-box" placeholder="ابحث عن صلاحية...">
            </div>
            <div class="row-2">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> اسم الحزمة</label>
                    <input type="text"  name="name" value="{{ old('name', $package->name) }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> الوصف </label>
                    <textarea name="description">{{ old('description', $package->description) }}</textarea>
                </div>
            </div>


            <h3>المدخلات العامة</h3>
            <ul class="departments">
                <li>
                    <strong>المستخدمون</strong> <input type="checkbox" class="checkbox parent" name="general[users]" value="1" @if(isset($permissions['general']['users'])) checked @endif >
                    <ul>
                        <li><strong>إضافة</strong> <input type="checkbox" name="general[users][create]" value="1" class="checkbox" @if(isset($permissions['general']['users']['create']) && $permissions['general']['users']['create'] == 1) checked @endif></li>
                        <li><strong>عرض</strong> <input type="checkbox" name="general[users][view]" value="1" class="checkbox" @if(isset($permissions['general']['users']['view']) && $permissions['general']['users']['view'] == 1) checked @endif></li>
                        <li><strong>تعديل</strong> <input type="checkbox" name="general[users][edit]" value="1" class="checkbox" @if(isset($permissions['general']['users']['edit']) && $permissions['general']['users']['edit'] == 1) checked @endif></li>
                        <li><strong>حذف</strong> <input type="checkbox" name="general[users][delete]" value="1" class="checkbox" @if(isset($permissions['general']['users']['delete']) && $permissions['general']['users']['delete'] == 1) checked @endif></li>
                    </ul>
                </li>
                <li>
                    <strong>الفروع</strong> <input type="checkbox" class="checkbox parent" name="general[branches]" value="1" @if(isset($permissions['general']['branches'])) checked @endif >
                    <ul>
                        <li><strong>إضافة</strong> <input type="checkbox" name="general[branches][create]" value="1" class="checkbox" @if(isset($permissions['general']['branches']['create']) && $permissions['general']['branches']['create'] == 1) checked @endif></li>
                        <li><strong>عرض</strong> <input type="checkbox" name="general[branches][view]" value="1" class="checkbox" @if(isset($permissions['general']['branches']['view']) && $permissions['general']['branches']['view'] == 1) checked @endif></li>
                        <li><strong>تعديل</strong> <input type="checkbox" name="general[branches][edit]" value="1" class="checkbox" @if(isset($permissions['general']['branches']['edit']) && $permissions['general']['branches']['edit'] == 1) checked @endif></li>
                        <li><strong>حذف</strong> <input type="checkbox" name="general[branches][delete]" value="1" class="checkbox" @if(isset($permissions['general']['branches']['delete']) && $permissions['general']['branches']['delete'] == 1) checked @endif></li>
                    </ul>
                </li>
                <li>
                    <strong>أنواع المشاكل</strong> <input type="checkbox" class="checkbox parent" name="general[issues]" value="1" @if(isset($permissions['general']['issues'])) checked @endif >
                    <ul>
                        <li><strong>إضافة </strong> <input type="checkbox" name="general[issues][create]" value="1" class="checkbox" @if(isset($permissions['general']['issues']['create']) && $permissions['general']['issues']['create'] == 1) checked @endif></li>
                        <li><strong>عرض</strong> <input type="checkbox" name="general[issues][view]" value="1" class="checkbox" @if(isset($permissions['general']['issues']['view']) && $permissions['general']['issues']['view'] == 1) checked @endif></li>
                        <li><strong>تعديل</strong> <input type="checkbox" name="general[issues][edit]" value="1" class="checkbox" @if(isset($permissions['general']['issues']['edit']) && $permissions['general']['issues']['edit'] == 1) checked @endif></li>
                        <li><strong>حذف</strong> <input type="checkbox" name="general[issues][delete]" value="1" class="checkbox" @if(isset($permissions['general']['issues']['delete']) && $permissions['general']['issues']['delete'] == 1) checked @endif></li>
                    </ul>
                </li>
                <li>
                    <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="general[control_units]" value="1" @if(isset($permissions['general']['control_units'])) checked @endif >
                    <ul>
                        <li><strong>إضافة </strong> <input type="checkbox" name="general[control_units][create]" value="1" class="checkbox" @if(isset($permissions['general']['control_units']['create']) && $permissions['general']['control_units']['create'] == 1) checked @endif></li>
                        <li><strong>عرض</strong> <input type="checkbox" name="general[control_units][view]" value="1" class="checkbox" @if(isset($permissions['general']['control_units']['view']) && $permissions['general']['control_units']['view'] == 1) checked @endif></li>
                        <li><strong>تعديل</strong> <input type="checkbox" name="general[control_units][edit]" value="1" class="checkbox" @if(isset($permissions['general']['control_units']['edit']) && $permissions['general']['control_units']['edit'] == 1) checked @endif></li>
                        <li><strong>حذف</strong> <input type="checkbox" name="general[control_units][delete]" value="1" class="checkbox" @if(isset($permissions['general']['control_units']['delete']) && $permissions['general']['control_units']['delete'] == 1) checked @endif></li>
                    </ul>
                </li>
            </ul>

            <!-- المخزون -->
            <h3>رقابة المخزون</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="1[insertions]" value="1" @if(isset($permissions['1']['insertions'])) checked @endif >
                    <ul>
                        <li>
                            <strong>الأصناف</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][products]" value="1" @if(isset($permissions['1']['insertions']['products'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][products][create]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['products']['create']) && $permissions['1']['insertions']['products']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][products][view]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['products']['view']) && $permissions['1']['insertions']['products']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][products][edit]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['products']['edit']) && $permissions['1']['insertions']['products']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][products][delete]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['products']['delete']) && $permissions['1']['insertions']['products']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الوحدات</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][units]" value="1" @if(isset($permissions['1']['insertions']['units'])) checked @endif  >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][units][create]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['units']['create']) && $permissions['1']['insertions']['units']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][units][view]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['units']['view']) && $permissions['1']['insertions']['units']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][units][edit]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['units']['edit']) && $permissions['1']['insertions']['units']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][units][delete]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['units']['delete']) && $permissions['1']['insertions']['units']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المخازن</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][stores]" value="1"  @if(isset($permissions['1']['insertions']['stores'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][stores][create]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['stores']['create']) && $permissions['1']['insertions']['stores']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][stores][view]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['stores']['view']) && $permissions['1']['insertions']['stores']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][stores][edit]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['stores']['edit']) && $permissions['1']['insertions']['stores']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][stores][delete]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['stores']['delete']) && $permissions['1']['insertions']['stores']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][controlUnits]" value="1"   @if(isset($permissions['1']['insertions']['controlUnits'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][controlUnits][create]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['controlUnits']['create']) && $permissions['1']['insertions']['controlUnits']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][controlUnits][view]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['controlUnits']['view']) && $permissions['1']['insertions']['controlUnits']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][controlUnits][edit]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['controlUnits']['edit']) && $permissions['1']['insertions']['controlUnits']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][controlUnits][delete]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['controlUnits']['delete']) && $permissions['1']['insertions']['controlUnits']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][main_groups]" value="1"  @if(isset($permissions['1']['insertions']['main_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][main_groups][create]" value="1" class="checkbox"  @if(isset($permissions['1']['insertions']['main_groups']['create']) && $permissions['1']['insertions']['main_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][main_groups][view]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['main_groups']['view']) && $permissions['1']['insertions']['main_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][main_groups][edit]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['main_groups']['edit']) && $permissions['1']['insertions']['main_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][main_groups][delete]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['main_groups']['delete']) && $permissions['1']['insertions']['main_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="1[insertions][sub_groups]" value="1"  @if(isset($permissions['1']['insertions']['sub_groups'])) checked @endif>
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[insertions][sub_groups][create]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['sub_groups']['create']) && $permissions['1']['insertions']['sub_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[insertions][sub_groups][view]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['sub_groups']['view']) && $permissions['1']['insertions']['sub_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[insertions][sub_groups][edit]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['sub_groups']['edit']) && $permissions['1']['insertions']['sub_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[insertions][sub_groups][delete]" value="1" class="checkbox" @if(isset($permissions['1']['insertions']['sub_groups']['delete']) && $permissions['1']['insertions']['sub_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="1[operations]" value="1"   @if(isset($permissions['1']['operations'])) checked @endif  >
                    <ul>
                        <li>
                            <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="1[operations][reports]" value="1"  @if(isset($permissions['1']['operations']['reports'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][reports][create]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['reports']['create']) && $permissions['1']['operations']['reports']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[operations][reports][view]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['reports']['view']) && $permissions['1']['operations']['reports']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][reports][edit]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['reports']['edit']) && $permissions['1']['operations']['reports']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[operations][reports][delete]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['reports']['delete']) && $permissions['1']['operations']['reports']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>التوالف</strong> <input type="checkbox" class="checkbox parent" name="1[operations][exp]" value="1"  @if(isset($permissions['1']['operations']['reports'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][exp][create]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['exp']['create']) && $permissions['1']['operations']['exp']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[operations][exp][view]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['exp']['view']) && $permissions['1']['operations']['exp']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][exp][edit]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['exp']['edit']) && $permissions['1']['operations']['exp']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[operations][exp][delete]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['exp']['delete']) && $permissions['1']['operations']['exp']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المرتجعات </strong> <input type="checkbox" class="checkbox parent" name="1[operations][ret]" value="1"  @if(isset($permissions['1']['operations']['reports'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][ret][create]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['ret']['create']) && $permissions['1']['operations']['ret']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[operations][ret][view]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['ret']['view']) && $permissions['1']['operations']['ret']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][ret][edit]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['ret']['edit']) && $permissions['1']['operations']['ret']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[operations][ret][delete]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['ret']['delete']) && $permissions['1']['operations']['ret']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>التحميلات </strong> <input type="checkbox" class="checkbox parent" name="1[operations][load]" value="1"  @if(isset($permissions['1']['operations']['reports'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="1[operations][load][create]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['load']['create']) && $permissions['1']['operations']['load']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="1[operations][load][view]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['load']['view']) && $permissions['1']['operations']['load']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="1[operations][load][edit]" value="1" class="checkbox" @if(isset($permissions['1']['operations']['load']['edit']) && $permissions['1']['operations']['load']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="1[operations][load][delete]" value="1" class="checkbox"  @if(isset($permissions['1']['operations']['load']['delete']) && $permissions['1']['operations']['load']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- باقي الأقسام بنفس الطريقة -->
            </ul>

            <h3>رقابة الأصول</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong> <input type="checkbox" class="checkbox parent" name="2[insertions]" value="1"  @if(isset($permissions['2']['insertions'])) checked @endif  >
                    <ul>
                        <li>
                            <strong>الأصول</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][assets]" value="1" @if(isset($permissions['2']['insertions']['assets'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][assets][create]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['assets']['create']) && $permissions['2']['insertions']['assets']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong>    <input type="checkbox" name="2[insertions][assets][view]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['assets']['view']) && $permissions['2']['insertions']['assets']['view'] == 1) checked @endif ></li>
                                <li><strong>تعديل</strong>  <input type="checkbox" name="2[insertions][assets][edit]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['assets']['edit']) && $permissions['2']['insertions']['assets']['edit'] == 1) checked @endif ></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][assets][delete]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['assets']['delete']) && $permissions['2']['insertions']['assets']['delete'] == 1) checked @endif ></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الوحدات الرقابية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][controlUnits]" value="1" @if(isset($permissions['2']['insertions']['controlUnits'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][controlUnits][create]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['controlUnits']['create']) && $permissions['2']['insertions']['controlUnits']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong>    <input type="checkbox" name="2[insertions][controlUnits][view]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['controlUnits']['view']) && $permissions['2']['insertions']['controlUnits']['view'] == 1) checked @endif ></li>
                                <li><strong>تعديل</strong>  <input type="checkbox" name="2[insertions][controlUnits][edit]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['controlUnits']['edit']) && $permissions['2']['insertions']['controlUnits']['edit'] == 1) checked @endif ></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][assets][controlUnits]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['controlUnits']['delete']) && $permissions['2']['insertions']['controlUnits']['delete'] == 1) checked @endif ></li>
                            </ul>
                        </li>


                        <li>
                            <strong>المجموعات الرئيسية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][main_groups]" value="1" @if(isset($permissions['2']['insertions']['main_groups'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][main_groups][create]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['main_groups']['create']) && $permissions['2']['insertions']['main_groups']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][main_groups][view]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['main_groups']['view']) && $permissions['2']['insertions']['main_groups']['view'] == 1) checked @endif ></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][main_groups][edit]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['main_groups']['edit']) && $permissions['2']['insertions']['main_groups']['edit'] == 1) checked @endif ></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][main_groups][delete]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['main_groups']['delete']) && $permissions['2']['insertions']['main_groups']['delete'] == 1) checked @endif ></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الفرعية</strong> <input type="checkbox" class="checkbox parent" name="2[insertions][sub_groups]" value="1"   @if(isset($permissions['2']['insertions']['sub_groups'])) checked @endif>
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="2[insertions][sub_groups][create]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['sub_groups']['create']) && $permissions['2']['insertions']['sub_groups']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[insertions][sub_groups][view]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['sub_groups']['view']) && $permissions['2']['insertions']['sub_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[insertions][sub_groups][edit]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['sub_groups']['edit']) && $permissions['2']['insertions']['sub_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[insertions][sub_groups][delete]" value="1" class="checkbox" @if(isset($permissions['2']['insertions']['sub_groups']['delete']) && $permissions['2']['insertions']['sub_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <strong>العمليات</strong> <input type="checkbox" class="checkbox parent" name="2[operations]" value="1"   @if(isset($permissions['2']['operations'])) checked @endif  >
                    <ul>
                        <li>
                            <strong>البلاغات</strong> <input type="checkbox" class="checkbox parent" name="2[operations][reports]" value="1"   @if(isset($permissions['2']['operations']['reports'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="2[operations][reports][create]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['reports']['create']) && $permissions['2']['operations']['reports']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[operations][reports][view]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['reports']['view']) && $permissions['2']['operations']['reports']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][reports][edit]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['reports']['edit']) && $permissions['2']['operations']['reports']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[operations][reports][delete]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['reports']['delete']) && $permissions['2']['operations']['reports']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>عمليات الصيانة</strong> <input type="checkbox" class="checkbox parent" name="2[operations][maintenance]" value="1"  @if(isset($permissions['2']['operations']['maintenance'])) checked @endif >
                            <ul>
                                <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][maintenance][create]" value="1" class="checkbox"  @if(isset($permissions['2']['operations']['maintenance']['create']) && $permissions['2']['operations']['maintenance']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[operations][maintenance][view]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance']['view']) && $permissions['2']['operations']['maintenance']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][maintenance][edit]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance']['edit']) && $permissions['2']['operations']['maintenance']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[operations][maintenance][delete]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance']['delete']) && $permissions['2']['operations']['maintenance']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>طلبات الصيانة</strong> <input type="checkbox" class="checkbox parent" name="2[operations][maintenance_request]" value="1"  @if(isset($permissions['2']['operations']['maintenance_request'])) checked @endif >
                            <ul>
                                <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][maintenance_request][create]" value="1" class="checkbox"  @if(isset($permissions['2']['operations']['maintenance_request']['create']) && $permissions['2']['operations']['maintenance_request']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[operations][maintenance_request][view]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance_request']['view']) && $permissions['2']['operations']['maintenance_request']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][maintenance_request][edit]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance_request']['edit']) && $permissions['2']['operations']['maintenance_request']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[operations][maintenance_request][delete]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance_request']['delete']) && $permissions['2']['operations']['maintenance_request']['delete'] == 1) checked @endif></li>
                                <li><strong>اعتماد</strong> <input type="checkbox" name="2[operations][maintenance_request][approve]" value="1" class="checkbox" @if(isset($permissions['2']['operations']['maintenance_request']['approve']) && $permissions['2']['operations']['maintenance_request']['approve'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>نقل الأصول</strong> <input type="checkbox" class="checkbox parent" name="2[operations][movements]" value="1"   @if(isset($permissions['2']['operations']['movements'])) checked @endif  >
                            <ul>
                                <li><strong>إضافة</strong> <input type="checkbox" name="2[operations][movements][create]" value="1" class="checkbox"   @if(isset($permissions['2']['operations']['movements']['create']) && $permissions['2']['operations']['movements']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="2[operations][movements][view]" value="1" class="checkbox"    @if(isset($permissions['2']['operations']['movements']['view']) && $permissions['2']['operations']['movements']['view'] == 1) checked @endif ></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="2[operations][movements][edit]" value="1" class="checkbox"    @if(isset($permissions['2']['operations']['movements']['edit']) && $permissions['2']['operations']['movements']['edit'] == 1) checked @endif ></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="2[operations][movements][delete]" value="1" class="checkbox"    @if(isset($permissions['2']['operations']['movements']['delete']) && $permissions['2']['operations']['movements']['delete'] == 1) checked @endif ></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <h3>رقابة العملاء</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong>
                    <input type="checkbox" class="checkbox parent" name="8[insertions]" value="1"
                           @if(isset($permissions['8']['insertions'])) checked @endif >
                    <ul>
                        <li>
                            <strong>العملاء</strong>
                            <input type="checkbox" class="checkbox parent" name="8[insertions][customers]" value="1"
                                   @if(isset($permissions['8']['insertions']['customers'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][customers][create]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['customers']['create']) && $permissions['8']['insertions']['customers']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][customers][view]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['customers']['view']) && $permissions['8']['insertions']['customers']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][customers][edit]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['customers']['edit']) && $permissions['8']['insertions']['customers']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][customers][delete]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['customers']['delete']) && $permissions['8']['insertions']['customers']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الوحدات الرقابية</strong>
                            <input type="checkbox" class="checkbox parent" name="8[insertions][controlUnits]" value="1"
                                   @if(isset($permissions['8']['insertions']['controlUnits'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][controlUnits][create]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['controlUnits']['create']) && $permissions['8']['insertions']['controlUnits']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][controlUnits][view]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['controlUnits']['view']) && $permissions['8']['insertions']['controlUnits']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][controlUnits][edit]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['controlUnits']['edit']) && $permissions['8']['insertions']['controlUnits']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][controlUnits][delete]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['controlUnits']['delete']) && $permissions['8']['insertions']['controlUnits']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>المجموعات الرئيسية</strong>
                            <input type="checkbox" class="checkbox parent" name="8[insertions][main_groups]" value="1"
                                   @if(isset($permissions['8']['insertions']['main_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][main_groups][create]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['main_groups']['create']) && $permissions['8']['insertions']['main_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][main_groups][view]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['main_groups']['view']) && $permissions['8']['insertions']['main_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][main_groups][edit]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['main_groups']['edit']) && $permissions['8']['insertions']['main_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][main_groups][delete]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['main_groups']['delete']) && $permissions['8']['insertions']['main_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الفرعية</strong>
                            <input type="checkbox" class="checkbox parent" name="8[insertions][sub_groups]" value="1"
                                   @if(isset($permissions['8']['insertions']['sub_groups'])) checked @endif>
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="8[insertions][sub_groups][create]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['sub_groups']['create']) && $permissions['8']['insertions']['sub_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[insertions][sub_groups][view]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['sub_groups']['view']) && $permissions['8']['insertions']['sub_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[insertions][sub_groups][edit]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['sub_groups']['edit']) && $permissions['8']['insertions']['sub_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[insertions][sub_groups][delete]" value="1" class="checkbox" @if(isset($permissions['8']['insertions']['sub_groups']['delete']) && $permissions['8']['insertions']['sub_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <strong>العمليات</strong>
                    <input type="checkbox" class="checkbox parent" name="8[operations]" value="1"
                           @if(isset($permissions['8']['operations'])) checked @endif >
                    <ul>
                        <li>
                            <strong>البلاغات</strong>
                            <input type="checkbox" class="checkbox parent" name="8[operations][reports]" value="1"
                                   @if(isset($permissions['8']['operations']['reports'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="8[operations][reports][create]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['reports']['create']) && $permissions['8']['operations']['reports']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[operations][reports][view]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['reports']['view']) && $permissions['8']['operations']['reports']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[operations][reports][edit]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['reports']['edit']) && $permissions['8']['operations']['reports']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[operations][reports][delete]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['reports']['delete']) && $permissions['8']['operations']['reports']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الإشراف</strong>
                            <input type="checkbox" class="checkbox parent" name="8[operations][supervises]" value="1"
                                   @if(isset($permissions['8']['operations']['supervises'])) checked @endif >
                            <ul>
                                <li><strong>إضافة</strong> <input type="checkbox" name="8[operations][supervises][create]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['supervises']['create']) && $permissions['8']['operations']['supervises']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="8[operations][supervises][view]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['supervises']['view']) && $permissions['8']['operations']['supervises']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="8[operations][supervises][edit]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['supervises']['edit']) && $permissions['8']['operations']['supervises']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="8[operations][supervises][delete]" value="1" class="checkbox" @if(isset($permissions['8']['operations']['supervises']['delete']) && $permissions['8']['operations']['supervises']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <h3>رقابة العمالة</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong>
                    <input type="checkbox" class="checkbox parent" name="4[insertions]" value="1"
                           @if(isset($permissions['4']['insertions'])) checked @endif >
                    <ul>
                        <li>
                            <strong>الموظفين</strong>
                            <input type="checkbox" class="checkbox parent" name="4[insertions][employees]" value="1"
                                   @if(isset($permissions['4']['insertions']['employees'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][employees][create]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['employees']['create']) && $permissions['4']['insertions']['employees']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][employees][view]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['employees']['view']) && $permissions['4']['insertions']['employees']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][employees][edit]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['employees']['edit']) && $permissions['4']['insertions']['employees']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][employees][delete]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['employees']['delete']) && $permissions['4']['insertions']['employees']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong> وحدات  التقييم  </strong>
                            <input type="checkbox" class="checkbox parent" name="4[insertions][ratingUnits]" value="1"
                                   @if(isset($permissions['4']['insertions']['ratingUnits'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][ratingUnits][create]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['ratingUnits']['create']) && $permissions['4']['insertions']['ratingUnits']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][ratingUnits][view]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['ratingUnits']['view']) && $permissions['4']['insertions']['ratingUnits']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][ratingUnits][edit]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['ratingUnits']['edit']) && $permissions['4']['insertions']['ratingUnits']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][ratingUnits][delete]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['ratingUnits']['delete']) && $permissions['4']['insertions']['ratingUnits']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الوحدات الرقابية</strong>
                            <input type="checkbox" class="checkbox parent" name="4[insertions][controlUnits]" value="1"
                                   @if(isset($permissions['4']['insertions']['controlUnits'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][controlUnits][create]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['controlUnits']['create']) && $permissions['4']['insertions']['controlUnits']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][controlUnits][view]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['controlUnits']['view']) && $permissions['4']['insertions']['controlUnits']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][controlUnits][edit]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['controlUnits']['edit']) && $permissions['4']['insertions']['controlUnits']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][controlUnits][delete]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['controlUnits']['delete']) && $permissions['4']['insertions']['controlUnits']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الرئيسية</strong>
                            <input type="checkbox" class="checkbox parent" name="4[insertions][main_groups]" value="1"
                                   @if(isset($permissions['4']['insertions']['main_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][main_groups][create]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['main_groups']['create']) && $permissions['4']['insertions']['main_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][main_groups][view]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['main_groups']['view']) && $permissions['4']['insertions']['main_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][main_groups][edit]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['main_groups']['edit']) && $permissions['4']['insertions']['main_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][main_groups][delete]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['main_groups']['delete']) && $permissions['4']['insertions']['main_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المجموعات الفرعية</strong>
                            <input type="checkbox" class="checkbox parent" name="4[insertions][sub_groups]" value="1"
                                   @if(isset($permissions['4']['insertions']['sub_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[insertions][sub_groups][create]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['sub_groups']['create']) && $permissions['4']['insertions']['sub_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[insertions][sub_groups][view]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['sub_groups']['view']) && $permissions['4']['insertions']['sub_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[insertions][sub_groups][edit]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['sub_groups']['edit']) && $permissions['4']['insertions']['sub_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[insertions][sub_groups][delete]" value="1" class="checkbox" @if(isset($permissions['4']['insertions']['sub_groups']['delete']) && $permissions['4']['insertions']['sub_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <strong>العمليات</strong>
                    <input type="checkbox" class="checkbox parent" name="4[operations]" value="1"
                           @if(isset($permissions['4']['operations'])) checked @endif >
                    <ul>
                        <li>
                            <strong>البلاغات</strong>
                            <input type="checkbox" class="checkbox parent" name="4[operations][reports]" value="1"
                                   @if(isset($permissions['4']['operations']['reports'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][reports][create]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['reports']['create']) && $permissions['4']['operations']['reports']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[operations][reports][view]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['reports']['view']) && $permissions['4']['operations']['reports']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][reports][edit]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['reports']['edit']) && $permissions['4']['operations']['reports']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[operations][reports][delete]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['reports']['delete']) && $permissions['4']['operations']['reports']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>التقييمات</strong>
                            <input type="checkbox" class="checkbox parent" name="4[operations][ratings]" value="1"
                                   @if(isset($permissions['4']['operations']['ratings'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][ratings][create]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['ratings']['create']) && $permissions['4']['operations']['ratings']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[operations][ratings][view]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['ratings']['view']) && $permissions['4']['operations']['ratings']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][ratings][edit]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['ratings']['edit']) && $permissions['4']['operations']['ratings']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[operations][ratings][delete]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['ratings']['delete']) && $permissions['4']['operations']['ratings']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>الرقابة اليومية</strong>
                            <input type="checkbox" class="checkbox parent" name="4[operations][monitoring]" value="1"
                                   @if(isset($permissions['4']['operations']['monitoring'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][monitoring][create]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['monitoring']['create']) && $permissions['4']['operations']['monitoring']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[operations][monitoring][view]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['monitoring']['view']) && $permissions['4']['operations']['monitoring']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][monitoring][edit]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['monitoring']['edit']) && $permissions['4']['operations']['monitoring']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[operations][monitoring][delete]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['monitoring']['delete']) && $permissions['4']['operations']['monitoring']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>المهام</strong>
                            <input type="checkbox" class="checkbox parent" name="4[operations][tasks]" value="1"
                                   @if(isset($permissions['4']['operations']['tasks'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="4[operations][tasks][create]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['tasks']['create']) && $permissions['4']['operations']['tasks']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="4[operations][tasks][view]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['tasks']['view']) && $permissions['4']['operations']['tasks']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="4[operations][tasks][edit]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['tasks']['edit']) && $permissions['4']['operations']['tasks']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="4[operations][tasks][delete]" value="1" class="checkbox" @if(isset($permissions['4']['operations']['tasks']['delete']) && $permissions['4']['operations']['tasks']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>


            <h3>رقابة الموردين</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong>
                    <input type="checkbox" class="checkbox parent" name="9[insertions]" value="1"
                           @if(isset($permissions['9']['insertions'])) checked @endif >
                    <ul>
                        <li>
                            <strong>الموردين</strong>
                            <input type="checkbox" class="checkbox parent" name="9[insertions][suppliers]" value="1"
                                   @if(isset($permissions['9']['insertions']['suppliers'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][suppliers][create]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['suppliers']['create']) && $permissions['9']['insertions']['suppliers']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][suppliers][view]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['suppliers']['view']) && $permissions['9']['insertions']['suppliers']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][suppliers][edit]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['suppliers']['edit']) && $permissions['9']['insertions']['suppliers']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][suppliers][delete]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['suppliers']['delete']) && $permissions['9']['insertions']['suppliers']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>الوحدات الرقابية</strong>
                            <input type="checkbox" class="checkbox parent" name="9[insertions][controlUnits]" value="1"
                                   @if(isset($permissions['9']['insertions']['controlUnits'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][controlUnits][create]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['controlUnits']['create']) && $permissions['9']['insertions']['controlUnits']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][controlUnits][view]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['controlUnits']['view']) && $permissions['9']['insertions']['controlUnits']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][controlUnits][edit]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['controlUnits']['edit']) && $permissions['9']['insertions']['controlUnits']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][controlUnits][delete]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['controlUnits']['delete']) && $permissions['9']['insertions']['controlUnits']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>المجموعات الرئيسية</strong>
                            <input type="checkbox" class="checkbox parent" name="9[insertions][main_groups]" value="1"
                                   @if(isset($permissions['9']['insertions']['main_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][main_groups][create]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['main_groups']['create']) && $permissions['9']['insertions']['main_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][main_groups][view]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['main_groups']['view']) && $permissions['9']['insertions']['main_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][main_groups][edit]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['main_groups']['edit']) && $permissions['9']['insertions']['main_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][main_groups][delete]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['main_groups']['delete']) && $permissions['9']['insertions']['main_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>المجموعات الفرعية</strong>
                            <input type="checkbox" class="checkbox parent" name="9[insertions][sub_groups]" value="1"
                                   @if(isset($permissions['9']['insertions']['sub_groups'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[insertions][sub_groups][create]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['sub_groups']['create']) && $permissions['9']['insertions']['sub_groups']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[insertions][sub_groups][view]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['sub_groups']['view']) && $permissions['9']['insertions']['sub_groups']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[insertions][sub_groups][edit]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['sub_groups']['edit']) && $permissions['9']['insertions']['sub_groups']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[insertions][sub_groups][delete]" value="1" class="checkbox" @if(isset($permissions['9']['insertions']['sub_groups']['delete']) && $permissions['9']['insertions']['sub_groups']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <strong>العمليات</strong>
                    <input type="checkbox" class="checkbox parent" name="9[operations]" value="1"
                           @if(isset($permissions['9']['operations'])) checked @endif >
                    <ul>
                        <li>
                            <strong>البلاغات</strong>
                            <input type="checkbox" class="checkbox parent" name="9[operations][reports]" value="1"
                                   @if(isset($permissions['9']['operations']['reports'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][reports][create]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['reports']['create']) && $permissions['9']['operations']['reports']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[operations][reports][view]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['reports']['view']) && $permissions['9']['operations']['reports']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][reports][edit]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['reports']['edit']) && $permissions['9']['operations']['reports']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[operations][reports][delete]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['reports']['delete']) && $permissions['9']['operations']['reports']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>الرقابة اليومية</strong>
                            <input type="checkbox" class="checkbox parent" name="9[operations][monitoring]" value="1"
                                   @if(isset($permissions['9']['operations']['monitoring'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][monitoring][create]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['monitoring']['create']) && $permissions['9']['operations']['monitoring']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[operations][monitoring][view]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['monitoring']['view']) && $permissions['9']['operations']['monitoring']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][monitoring][edit]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['monitoring']['edit']) && $permissions['9']['operations']['monitoring']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[operations][monitoring][delete]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['monitoring']['delete']) && $permissions['9']['operations']['monitoring']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>

                        <li>
                            <strong>المهام</strong>
                            <input type="checkbox" class="checkbox parent" name="9[operations][tasks]" value="1"
                                   @if(isset($permissions['9']['operations']['tasks'])) checked @endif >
                            <ul>
                                <li><strong>إضافة </strong> <input type="checkbox" name="9[operations][tasks][create]" value="1" class="checkbox"  @if(isset($permissions['9']['operations']['tasks']['create']) && $permissions['9']['operations']['tasks']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="9[operations][tasks][view]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['tasks']['view']) && $permissions['9']['operations']['tasks']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="9[operations][tasks][edit]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['tasks']['edit']) && $permissions['9']['operations']['tasks']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="9[operations][tasks][delete]" value="1" class="checkbox" @if(isset($permissions['9']['operations']['tasks']['delete']) && $permissions['9']['operations']['tasks']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <h3>المهام</h3>
            <ul class="departments">
                <li>
                    <strong>المدخلات</strong>
                    <input type="checkbox" class="checkbox parent" name="5[insertions]" value="1"
                           @if(isset($permissions['5']['insertions'])) checked @endif >
                    <ul>
                        <li>
                            <strong>المهام</strong>
                            <input type="checkbox" class="checkbox parent" name="5[insertions][tasks]" value="1"
                                   @if(isset($permissions['5']['insertions']['tasks'])) checked @endif >
                            <ul>
                                <li><strong>إضافة  </strong> <input type="checkbox" name="5[insertions][tasks][create]" value="1" class="checkbox" @if(isset($permissions['5']['insertions']['tasks']['create']) && $permissions['5']['insertions']['tasks']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="5[insertions][tasks][view]" value="1" class="checkbox" @if(isset($permissions['5']['insertions']['tasks']['view']) && $permissions['5']['insertions']['tasks']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="5[insertions][tasks][edit]" value="1" class="checkbox" @if(isset($permissions['5']['insertions']['tasks']['edit']) && $permissions['5']['insertions']['tasks']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="5[insertions][tasks][delete]" value="1" class="checkbox" @if(isset($permissions['5']['insertions']['tasks']['delete']) && $permissions['5']['insertions']['tasks']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <strong>العمليات</strong>
                    <input type="checkbox" class="checkbox parent" name="5[operations]" value="1"
                           @if(isset($permissions['5']['operations'])) checked @endif >
                    <ul>
                        <li>
                            <strong>إسنادات المهام</strong>
                            <input type="checkbox" class="checkbox parent" name="5[operations][assignments]" value="1"
                                   @if(isset($permissions['5']['operations']['assignments'])) checked @endif >
                            <ul>
                                <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][assignments][create]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['assignments']['create']) && $permissions['5']['operations']['assignments']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="5[operations][assignments][view]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['assignments']['view']) && $permissions['5']['operations']['assignments']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][assignments][edit]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['assignments']['edit']) && $permissions['5']['operations']['assignments']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="5[operations][assignments][delete]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['assignments']['delete']) && $permissions['5']['operations']['assignments']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong>استلامات المهام</strong>
                            <input type="checkbox" class="checkbox parent" name="5[operations][receipts]" value="1"
                                   @if(isset($permissions['5']['operations']['receipts'])) checked @endif >
                            <ul>
                                <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][receipts][create]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['receipts']['create']) && $permissions['5']['operations']['receipts']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="5[operations][receipts][view]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['receipts']['view']) && $permissions['5']['operations']['receipts']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][receipts][edit]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['receipts']['edit']) && $permissions['5']['operations']['receipts']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="5[operations][receipts][delete]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['receipts']['delete']) && $permissions['5']['operations']['receipts']['delete'] == 1) checked @endif></li>
                                <li><strong>اعتماد وتقييم </strong> <input type="checkbox" name="5[operations][receipts][approve]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['receipts']['approve']) && $permissions['5']['operations']['receipts']['approve'] == 1) checked @endif></li>
                            </ul>
                        </li>
                        <li>
                            <strong> المهام اليومية </strong>
                            <input type="checkbox" class="checkbox parent" name="5[operations][myTask]" value="1"
                                   @if(isset($permissions['5']['operations']['myTask'])) checked @endif >
                            <ul>
                                <li><strong>إضافة  </strong> <input type="checkbox" name="5[operations][myTask][create]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['myTask']['create']) && $permissions['5']['operations']['myTask']['create'] == 1) checked @endif></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="5[operations][myTask][view]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['myTask']['view']) && $permissions['5']['operations']['myTask']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="5[operations][myTask][edit]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['myTask']['edit']) && $permissions['5']['operations']['myTask']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="5[operations][myTask][delete]" value="1" class="checkbox" @if(isset($permissions['5']['operations']['myTask']['delete']) && $permissions['5']['operations']['myTask']['delete'] == 1) checked @endif></li>
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
                            <strong>التقارير اليومية</strong> <input type="checkbox" class="checkbox parent" name="daily_monitoring[daily_monitoring]" value="1" @if(isset($permissions['daily_monitoring']['daily_monitoring'])) checked @endif   >
                            <ul>
                                <li><strong>إضافة  </strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][create]" value="1" class="checkbox" @if(isset($permissions['daily_monitoring']['daily_monitoring']['create']) && $permissions['daily_monitoring']['daily_monitoring']['create'] == 1) checked @endif ></li>
                                <li><strong>عرض</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][view]" value="1" class="checkbox" @if(isset($permissions['daily_monitoring']['daily_monitoring']['view']) && $permissions['daily_monitoring']['daily_monitoring']['view'] == 1) checked @endif></li>
                                <li><strong>تعديل</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][edit]" value="1" class="checkbox" @if(isset($permissions['daily_monitoring']['daily_monitoring']['edit']) && $permissions['daily_monitoring']['daily_monitoring']['edit'] == 1) checked @endif></li>
                                <li><strong>حذف</strong> <input type="checkbox" name="daily_monitoring[daily_monitoring][delete]" value="1" class="checkbox" @if(isset($permissions['daily_monitoring']['daily_monitoring']['delete']) && $permissions['daily_monitoring']['daily_monitoring']['delete'] == 1) checked @endif></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>



            <div class="actions">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save" style="margin-left: 8px;"></i>
                    حفظ التعديلات
                </button>
                <a href="{{ route('packages.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times" style="margin-left: 8px;"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // تهيئة parent checkboxes بناء على حالة الأطفال
            const parentCheckboxes = document.querySelectorAll('.parent');
            parentCheckboxes.forEach(parent => {
                const children = parent.closest('li').querySelectorAll('ul input[type="checkbox"]');
                let checkedCount = 0;
                children.forEach(child => {
                    if (child.checked) {
                        checkedCount++;
                    }
                });

                if (checkedCount === children.length) {
                    parent.checked = true;
                } else if (checkedCount > 0) {
                    parent.indeterminate = true;
                }
            });

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
