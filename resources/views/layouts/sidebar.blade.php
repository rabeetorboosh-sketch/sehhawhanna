<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<aside class="sidebar show" id="sidebar">
    <h2 class="logo">نظام الرقابة</h2>

    <ul id="customMenu" class="custom-menu">
        <li data-action="openNewTab">فتح في تبويب جديد</li>
        <li data-action="addShortcut">
            <form id="shortcutForm" action="{{ route('shorts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="name" id="shortcutName">
                <input type="hidden" name="url" id="shortcutUrl">
                <input type="submit" value="إضافة إلى الاختصارات">
            </form>
        </li>
    </ul>
    <ul class="menu">

{{--        <li class="menu-item">--}}
{{--            <button class="dropdown-btn"> تهيئة النظام</button>--}}
{{--            <ul class="submenu">--}}
{{--                <li><a href="#"> اللغة </a></li>--}}
{{--                <li><a href="#">---</a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        @if(Auth::user()->sectionsPermissions('general'))
        <li class="menu-item">

            <button class="dropdown-btn">المدخلات العامة </button>
            <ul class="submenu">
                <li class="menu-item">
                    @if(Auth::user()->permissions('general-users')?->can_show==1)
                    <button class="dropdown-btn"> المستخدمون </button>
                    <ul class="submenu">
                        @if(Auth::user()->permissions('general-users')?->can_create==1)
                        <li><a href="{{route('users.create')}}"> إضافة مستخدم</a></li>
                        @endif
                        <li><a href="{{route('users.index')}}">قائمة المستحدمين</a></li>
                         @if(Auth::user()->permissions('general-users')?->can_create==1)
                        <li><a href="{{route('packages.index')}}"> حزم الصلاحيات </a></li>
                         @endif
                    </ul>
                    @endif
                </li>
{{--                <li class="menu-item">--}}
{{--                    <button class="dropdown-btn"> الفروع </button>--}}
{{--                    <ul class="submenu">--}}
{{--                        <li><a href="#">إضافة </a></li>--}}
{{--                        <li><a href="#">قائمة الفروع</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                @if(Auth::user()->permissions('general-issues')?->can_show==1)
                <li class="menu-item">
                    <button class="dropdown-btn">أنواع المشاكل</button>
                    <ul class="submenu">
                        @if(Auth::user()->permissions('general-issues')?->can_create==1)
                        <li><a href="{{ route('issuesType.add') }}"> إضافة نوع مشاكل</a></li>
                        @endif
                        <li><a href="{{ route('issuesType.index') }}">قائمة انواع المشاكل </a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->permissions('general-control_units')?->can_show==1)
                <li class="menu-item">
                    <button class="dropdown-btn">الوحدات الرقابية</button>
                    <ul class="submenu">
                        @if(Auth::user()->permissions('general-control_units')?->can_create==1)
                        <li><a href="{{ route('controlUnit.create') }}">إضافة وحدة رقابية </a></li>
                        @endif
                        <li><a href="{{ route('controlUnit.index') }}">قائمة الوحدات الرقابية </a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(Auth::user()->sectionsPermissions('1'))
        <li class="menu-item">

            <button class="dropdown-btn"> رقابة المخزون </button>
            <ul class="submenu">
                @if(Auth::user()->sectionsPermissions('1-insertions'))
                <li class="menu-item">
                    <button class="dropdown-btn">المدخلات</button>
                    <ul class="submenu">
                        @if(Auth::user()->permissions('1-insertions-products')?->can_show==1)
                        <li class="menu-item">
                            <button class="dropdown-btn">الأصناف</button>

                            <ul class="submenu">
                                @if(Auth::user()->permissions('1-insertions-products')?->can_create==1)
                                <li><a href="{{ route('items.create') }}">إضافة صنف</a></li>
                                @endif
                                <li><a href="{{ route('items.index') }}">قائمة الأصناف</a></li>
                            </ul>
                        </li>
                        @endif
                            @if(Auth::user()->permissions('1-insertions-units')?->can_show == 1)
                                <li class="menu-item">
                                    <button class="dropdown-btn">الوحدات</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('1-insertions-units')?->can_create == 1)
                                            <li><a href="{{ route('units.add') }}">إضافة وحدة</a></li>
                                        @endif
                                        <li><a href="{{ route('units.index') }}">قائمة الوحدات</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::user()->permissions('1-insertions-controlUnits')?->can_show == 1)
                                <li class="menu-item">
                                    <button class="dropdown-btn">الوحدات الرقابية</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('1-insertions-controlUnits')?->can_create == 1)
                                            <li><a href="{{ route('controlUnit.create',1) }}">إضافة وحدة رقابية </a></li>
                                        @endif
                                        <li><a href="{{ route('controlUnit.index',1) }} ">قائمة الوحدات الرقابية</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::user()->permissions('1-insertions-stores')?->can_show == 1)
                                <li class="menu-item">
                                    <button class="dropdown-btn">المخازن</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('1-insertions-stores')?->can_create == 1)
                                            <li><a href="{{ route('stores.create') }}">إضافة مخزن</a></li>
                                        @endif
                                        <li><a href="{{ route('stores.index') }}">قائمة المخازن</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::user()->permissions('1-insertions-main_groups')?->can_show == 1)
                                <li class="menu-item">
                                    <button class="dropdown-btn">المجموعات الرئيسية</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('1-insertions-main_groups')?->can_create == 1)
                                            <li><a href="{{ route('mainGroup.add',1) }}">إضافة مجموعة</a></li>
                                        @endif
                                        <li><a href="{{ route('mainGroup.index',1) }}">قائمة المجموعات</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if(Auth::user()->permissions('1-insertions-sub_groups')?->can_show == 1)
                                <li class="menu-item">
                                    <button class="dropdown-btn">المجموعات الفرعية</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('1-insertions-sub_groups')?->can_create == 1)
                                            <li><a href="{{ route('subGroup.add',1) }}">إضافة مجموعة</a></li>
                                        @endif
                                        <li><a href="{{ route('subGroup.index',1) }}">قائمة المجموعات</a></li>
                                    </ul>
                                </li>
                            @endif

                    </ul>
                </li>
                @endif
               @if(Auth::user()->sectionsPermissions('1-operations'))
                <li class="menu-item">
                    <button class="dropdown-btn">العمليات</button>
                    <ul class="submenu">
                        @if(Auth::user()->permissions('1-operations-reports')?->can_show == 1)
                            <li class="menu-item">
                                <button class="dropdown-btn">البلاغات</button>
                                <ul class="submenu">
                                    @if(Auth::user()->permissions('1-operations-reports')?->can_create == 1)
                                        <li><a href="{{ route('reports.create',1) }}">إضافة بلاغ مخزني </a></li>
                                    @endif
                                    <li><a href="{{ route('reports.index',1) }}"> قائمة البلاغات المخزنية </a></li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->permissions('1-operations-exp')?->can_show == 1)
                            <li class="menu-item">
                                <button class="dropdown-btn">التوالف</button>
                                <ul class="submenu">
                                    @if(Auth::user()->permissions('1-operations-exp')?->can_create == 1)
                                        <li><a href="{{ route('storeMovements.create',1) }}">إضافة توالف</a></li>
                                    @endif
                                    <li><a href="{{ route('storeMovements.index',1) }}">قائمة التوالف</a></li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->permissions('1-operations-ret')?->can_show == 1)
                            <li class="menu-item">
                                <button class="dropdown-btn">مرتجعات</button>
                                <ul class="submenu">
                                    @if(Auth::user()->permissions('1-operations-ret')?->can_create == 1)
                                        <li><a href="{{ route('storeMovements.create',2) }}">إضافة مرتجعات</a></li>
                                    @endif
                                    <li><a href="{{ route('storeMovements.index',2) }}">قائمة المرتجعات</a></li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->permissions('1-operations-load')?->can_show == 1)
                            <li class="menu-item">
                                <button class="dropdown-btn">التحميلات</button>
                                <ul class="submenu">
                                    @if(Auth::user()->permissions('1-operations-load')?->can_create == 1)
                                        <li><a href="{{ route('storeMovements.create',3) }}">إضافة تحميل</a></li>
                                    @endif
                                    <li><a href="{{ route('storeMovements.index',3) }}">قائمة التحميلات </a></li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                            <li class="menu-item">
                                <button class="dropdown-btn">الرقابة اليومية</button>
                                <ul class="submenu">
                                    @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                        <li><a href="{{ route('monitoring.partCreate',1) }}">إضافة رقابة يومية </a></li>
                                    @endif
                                    <li><a href="{{ route('monitoring.index',1) }}">قائمة الرقابة يومية</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
               @endif
            </ul>
        </li>
        @endif
        @if(Auth::user()->sectionsPermissions('2'))
            <li class="menu-item">
                <button class="dropdown-btn">رقابة الأصول</button>
                <ul class="submenu">

                    {{-- المدخلات --}}
                    @if(Auth::user()->sectionsPermissions('2-insertions'))
                        <li class="menu-item">
                            <button class="dropdown-btn">المدخلات</button>
                            <ul class="submenu">
                                {{-- الأصول --}}
                                @if(Auth::user()->permissions('2-insertions-assets')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الأصول</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-insertions-assets')?->can_create == 1)
                                                <li><a href="{{ route('asset.add') }}">إضافة أصل</a></li>
                                            @endif
                                            <li><a href="{{ route('asset.index') }}">قائمة الأصول</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('2-insertions-controlUnits')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الوحدات الرقابية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-insertions-controlUnits')?->can_create == 1)
                                                <li><a href="{{ route('controlUnit.create',2) }}">إضافة وحدة رقابية</a></li>
                                            @endif
                                            <li><a href="{{ route('controlUnit.index',2) }}">قائمة الوحدات الرقابية</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الرئيسية --}}
                                @if(Auth::user()->permissions('2-insertions-main_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الرئيسية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-insertions-main_groups')?->can_create == 1)
                                                <li><a href="{{ route('mainGroup.add',2) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('mainGroup.index',2) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الفرعية --}}
                                @if(Auth::user()->permissions('2-insertions-sub_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الفرعية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-insertions-sub_groups')?->can_create == 1)
                                                <li><a href="{{ route('subGroup.add',2) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('subGroup.index',2) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- العمليات --}}
                    @if(Auth::user()->sectionsPermissions('2-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">العمليات</button>
                            <ul class="submenu">
                                {{-- البلاغات --}}
                                @if(Auth::user()->permissions('2-operations-reports')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">البلاغات</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-operations-reports')?->can_create == 1)
                                                <li><a href="{{ route('reports.create',2) }}">إضافة بلاغ اصول</a></li>
                                            @endif
                                            <li><a href="{{ route('reports.index',2) }}">قائمة بلاغات الاصول </a></li>
                                        </ul>
                                    </li>
                                @endif


                                @if(Auth::user()->permissions('2-operations-maintenance_request')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">طلبات الصيانة</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-operations-maintenance_request')?->can_create == 1)
                                                <li><a href="{{ route('maintenance_requests.create') }}">إضافة طلب صيانة </a></li>
                                            @endif
                                            <li><a href="{{ route('maintenance_requests.index') }}">قائمة طلبات الصيانة</a></li>
                                        </ul>
                                    </li>
                                @endif

                                @if(Auth::user()->permissions('2-operations-maintenance')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">عمليات الصيانة</button>
                                        <ul class="submenu">
                                            <li><a href="{{ route('maintenance_solutions.index') }}">قائمة طلبات الصيانة</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- نقل الأصول --}}
                                @if(Auth::user()->permissions('2-operations-movements')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">نقل الأصول</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('2-operations-movements')?->can_create == 1)
                                                <li><a href="{{ route('asset_movements.create') }}">إضافة نقل اصل</a></li>
                                            @endif
                                            <li><a href="{{ route('asset_movements.index') }}">قائمة حركات الأصول</a></li>
                                        </ul>
                                    </li>
                                @endif

                                @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الرقابة اليومية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                                <li><a href="{{ route('monitoring.partCreate',2) }}">إضافة رقابة  يومية </a></li>
                                            @endif
                                            <li><a href="{{ route('monitoring.index',2) }}">قائمة الرقابة  يومية</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>
        @endif


        @if(Auth::user()->sectionsPermissions('8'))
            <li class="menu-item">
                <button class="dropdown-btn">رقابة العملاء</button>
                <ul class="submenu">

                    {{-- المدخلات --}}
                    @if(Auth::user()->sectionsPermissions('8-insertions'))
                        <li class="menu-item">
                            <button class="dropdown-btn">المدخلات</button>
                            <ul class="submenu">

                                {{-- العملاء --}}
                                @if(Auth::user()->permissions('8-insertions-customers')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">العملاء</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-insertions-customers')?->can_create == 1)
                                                <li><a href="{{ route('customers.create') }}">إضافة عميل</a></li>
                                            @endif
                                            <li><a href="{{ route('customers.index') }}">قائمة العملاء</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('8-insertions-sales_routs')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الخطوط</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-insertions-sales_routs')?->can_create == 1)
                                                <li><a href="{{ route('sales_routs.create') }}">إضافة خط</a></li>
                                            @endif
                                            <li><a href="{{ route('sales_routs.index') }}">قائمة الخطوط</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- الوحدات الرقابية --}}
                                @if(Auth::user()->permissions('8-insertions-controlUnits')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الوحدات الرقابية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-insertions-controlUnits')?->can_create == 1)
                                                <li><a href="{{ route('controlUnit.create',8) }}">إضافة وحدة</a></li>
                                            @endif
                                            <li><a href="{{ route('controlUnit.index',8) }}">قائمة الوحدات</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الرئيسية --}}
                                @if(Auth::user()->permissions('8-insertions-main_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الرئيسية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-insertions-main_groups')?->can_create == 1)
                                                <li><a href="{{ route('mainGroup.add',8) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('mainGroup.index',8) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الفرعية --}}
                                @if(Auth::user()->permissions('8-insertions-sub_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الفرعية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-insertions-sub_groups')?->can_create == 1)
                                                <li><a href="{{ route('subGroup.add',8) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('subGroup.index',8) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- العمليات --}}
                    @if(Auth::user()->sectionsPermissions('8-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">العمليات</button>
                            <ul class="submenu">

                                {{-- البلاغات --}}
                                @if(Auth::user()->permissions('8-operations-reports')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">البلاغات</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-operations-reports')?->can_create == 1)
                                                <li><a href="{{ route('reports.create',8) }}">إضافة بلاغ عملاء</a></li>
                                            @endif
                                            <li><a href="{{ route('reports.index',8) }}">قائمة بلاغات العملاء </a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- الإشراف --}}
                                @if(Auth::user()->permissions('8-operations-supervises')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الإشراف</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-operations-supervises')?->can_create == 1)
                                                <li><a href="{{ route('supervises.create') }}">إضافة عملية اشراف</a></li>
                                            @endif
                                            <li><a href="{{ route('supervises.index') }}">قائمة عمليات الإشراف</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('8-operations-customersRequests')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">طلبات العملاء</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('8-operations-customersRequests')?->can_create == 1)
                                                <li><a href="{{ route('customersRequests.create') }}">إضافة طلب عميل</a></li>
                                            @endif
                                            <li><a href="{{ route('customersRequests.index') }}">قائمة طلبات العملاء</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الرقابة اليومية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                                <li><a href="{{ route('monitoring.partCreate',8) }}">إضافة رقابة يومية </a></li>
                                            @endif
                                            <li><a href="{{ route('monitoring.index',8) }}">قائمة الرقابة يومية</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>
        @endif

        @if(Auth::user()->sectionsPermissions('4'))
            <li class="menu-item">
                <button class="dropdown-btn">رقابة العمالة</button>
                <ul class="submenu">

                    {{-- المدخلات --}}
                    @if(Auth::user()->sectionsPermissions('4-insertions'))
                        <li class="menu-item">
                            <button class="dropdown-btn">المدخلات</button>
                            <ul class="submenu">

                                {{-- الموظفين --}}
                                @if(Auth::user()->permissions('4-insertions-employeesTypes')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">انواع الموظفين</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-employeesTypes')?->can_create == 1)
                                                <li><a href="{{ route('employeeType.add') }}">إضافة نوع وظيفي</a></li>
                                            @endif
                                            <li><a href="{{ route('employeeType.index') }}">قائمة الانواع الوظيفية</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('4-insertions-employees')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الموظفين</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-employees')?->can_create == 1)
                                                <li><a href="{{ route('employees.create') }}">إضافة موظف</a></li>
                                            @endif
                                            <li><a href="{{ route('employees.index') }}">قائمة الموظفين</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('4-insertions-housing_units')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الوحدات السكنية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-housing_units')?->can_create == 1)
                                                <li><a href="{{ route('housing_units.create') }}">إضافة وحدة سكنية</a></li>
                                            @endif
                                            <li><a href="{{ route('housing_units.index') }}">قائمة الوحدات السكنية</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- الوحدات الرقابية --}}
                                @if(Auth::user()->permissions('4-insertions-controlUnits')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الوحدات الرقابية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-controlUnits')?->can_create == 1)
                                                <li><a href="{{ route('controlUnit.create',4) }}">إضافة وحدة</a></li>
                                            @endif
                                            <li><a href="{{ route('controlUnit.index',4) }}">قائمة الوحدات</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('4-insertions-ratingUnits')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">وحدات التقييم  </button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-ratingUnits')?->can_create == 1)
                                                <li><a href="{{ route('rating_units.create') }}"> إضافة وحدة تقييم </a></li>
                                            @endif
                                            <li><a href="{{ route('rating_units.index') }}">قائمة الوحدات التقييم</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الرئيسية --}}
                                @if(Auth::user()->permissions('4-insertions-main_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الرئيسية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-main_groups')?->can_create == 1)
                                                <li><a href="{{ route('mainGroup.add',4) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('mainGroup.index',4) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- المجموعات الفرعية --}}
                                @if(Auth::user()->permissions('4-insertions-sub_groups')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المجموعات الفرعية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-insertions-sub_groups')?->can_create == 1)
                                                <li><a href="{{ route('subGroup.add',4) }}">إضافة مجموعة</a></li>
                                            @endif
                                            <li><a href="{{ route('subGroup.index',4) }}">قائمة المجموعات</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- العمليات --}}
                    @if(Auth::user()->sectionsPermissions('4-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">العمليات</button>
                            <ul class="submenu">

                                {{-- البلاغات --}}
                                @if(Auth::user()->permissions('4-operations-reports')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">البلاغات</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-operations-reports')?->can_create == 1)
                                                <li><a href="{{ route('reports.create',4) }}">إضافة بلاغ</a></li>
                                            @endif
                                            <li><a href="{{ route('reports.index',4) }}">قائمة البلاغات</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('4-operations-ratings')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">التقييمات</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-operations-ratings')?->can_create == 1)
                                                <li><a href="{{ route('ratings.create') }}">إضافة تقييم</a></li>
                                            @endif
                                            <li><a href="{{ route('ratings.index') }}">قائمة التقييمات</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('4-operations-housing_assignments')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">التسكين</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('4-operations-housing_assignments')?->can_create == 1)
                                                <li><a href="{{ route('housing_assignments.create') }}">إضافة تسكين</a></li>
                                            @endif
                                            <li><a href="{{ route('housing_assignments.index') }}">قائمة التسكين</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الرقابة اليومية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                                <li><a href="{{ route('monitoring.partCreate',4) }}">إضافة رقابة يومية </a></li>
                                            @endif
                                            <li><a href="{{ route('monitoring.index',4) }}">قائمة الرقابة يومية</a></li>
                                        </ul>
                                    </li>
                                @endif

{{--                                --}}{{-- الرقابة اليومية --}}
{{--                                @if(Auth::user()->permissions('4-operations-monitoring')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">الرقابة اليومية</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('4-operations-monitoring')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('monitoring.create') }}">إضافة تقرير يومي</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('monitoring.index') }}">قائمة التقارير اليومية</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

                                {{-- المهام --}}
{{--                                @if(Auth::user()->permissions('4-operations-tasks')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">المهام</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('4-operations-tasks')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('tasks.create') }}">إضافة تقرير يومي</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('tasks.index') }}">قائمة التقارير اليومية</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
{{--        @if(Auth::user()->sectionsPermissions('9'))--}}
{{--            <li class="menu-item">--}}
{{--                <button class="dropdown-btn">رقابة الموردين</button>--}}
{{--                <ul class="submenu">--}}

{{--                    --}}{{-- المدخلات --}}
{{--                    @if(Auth::user()->sectionsPermissions('9-insertions'))--}}
{{--                        <li class="menu-item">--}}
{{--                            <button class="dropdown-btn">المدخلات</button>--}}
{{--                            <ul class="submenu">--}}

{{--                                --}}{{-- الموردين --}}
{{--                                @if(Auth::user()->permissions('9-insertions-suppliers')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">الموردين</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('9-insertions-suppliers')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('suppliers.create') }}">إضافة مورد</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('suppliers.index') }}">قائمة الموردين</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

{{--                                --}}{{-- الوحدات الرقابية --}}
{{--                                @if(Auth::user()->permissions('9-insertions-controlUnits')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">الوحدات الرقابية</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('9-insertions-controlUnits')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('controlUnit.create',9) }}">إضافة وحدة</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('controlUnit.index',9) }}">قائمة الوحدات</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

{{--                                --}}{{-- المجموعات الرئيسية --}}
{{--                                @if(Auth::user()->permissions('9-insertions-main_groups')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">المجموعات الرئيسية</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('9-insertions-main_groups')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('mainGroup.add',9) }}">إضافة مجموعة</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('mainGroup.index',9) }}">قائمة المجموعات</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

{{--                                --}}{{-- المجموعات الفرعية --}}
{{--                                @if(Auth::user()->permissions('9-insertions-sub_groups')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">المجموعات الفرعية</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('9-insertions-sub_groups')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('subGroup.add',9) }}">إضافة مجموعة</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('subGroup.index',9) }}">قائمة المجموعات</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                    --}}{{-- العمليات --}}
{{--                    @if(Auth::user()->sectionsPermissions('9-operations'))--}}
{{--                        <li class="menu-item">--}}
{{--                            <button class="dropdown-btn">العمليات</button>--}}
{{--                            <ul class="submenu">--}}

{{--                                --}}{{-- البلاغات --}}
{{--                                @if(Auth::user()->permissions('9-operations-reports')?->can_show == 1)--}}
{{--                                    <li class="menu-item">--}}
{{--                                        <button class="dropdown-btn">البلاغات</button>--}}
{{--                                        <ul class="submenu">--}}
{{--                                            @if(Auth::user()->permissions('9-operations-reports')?->can_create == 1)--}}
{{--                                                <li><a href="{{ route('reports.create',['department' => 9]) }}">إضافة بلاغ</a></li>--}}
{{--                                            @endif--}}
{{--                                            <li><a href="{{ route('reports.index',['department' => 9]) }}">قائمة البلاغات</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}


{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                </ul>--}}
{{--            </li>--}}
{{--        @endif--}}

        @if(Auth::user()->sectionsPermissions('5'))
            <li class="menu-item">
                <button class="dropdown-btn">المهام</button>
                <ul class="submenu">

                    {{-- المدخلات --}}
                    @if(Auth::user()->sectionsPermissions('5-insertions'))
                        <li class="menu-item">
                            <button class="dropdown-btn">المدخلات</button>
                            <ul class="submenu">

                                {{-- المهام --}}
                                @if(Auth::user()->permissions('5-insertions-tasks')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">المهام</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('5-insertions-tasks')?->can_create == 1)
                                                <li><a href="{{ route('tasks.create') }}">إضافة مهمة</a></li>
                                            @endif
                                            <li><a href="{{ route('tasks.index') }}">قائمة المهام</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                    {{-- العمليات --}}
                    @if(Auth::user()->sectionsPermissions('5-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">العمليات</button>
                            <ul class="submenu">

                                {{-- إسنادات المهام --}}
                                @if(Auth::user()->permissions('5-operations-assignments')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">إسنادات المهام</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('5-operations-assignments')?->can_create == 1)
                                                <li>
                                                    <a href="{{ route('task_assignments.list') }}">
                                                          اسناد المهام
                                                    </a>

                                                </li>
                                            @endif
                                            <li><a href="{{ route('task_assignments.index') }}">قائمة اسنادات المهام </a></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- استلامات المهام --}}
                                @if(Auth::user()->permissions('5-operations-receipts')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">استلامات المهام</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('5-operations-receipts')?->can_create == 1)
                                                <li><a href="{{ route('task_receipts.create') }}">إضافة استلام مهام </a></li>
                                            @endif
                                            <li><a href="{{ route('task_receipts.index') }}">قائمة استلامات المهام</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('5-operations-myTask')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">مهامي اليومية</button>
                                        <ul class="submenu">
                                            <li><a href="{{ route('myTask.index') }}">قائمة مهامي</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">الرقابة اليومية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                                <li><a href="{{ route('monitoring.partCreate',5) }}">إضافة رقابة يومية </a></li>
                                            @endif
                                            <li><a href="{{ route('monitoring.index',5) }}">قائمة الرقابة يومية</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>
        @endif

        @if(Auth::user()->sectionsPermissions('daily_monitoring'))
            <li class="menu-item">
                <button class="dropdown-btn">الرقابة اليومية</button>
                <ul class="submenu">
                    @if(Auth::user()->sectionsPermissions('daily_monitoring'))
                        <li class="menu-item">
                            <button class="dropdown-btn">العمليات</button>
                            <ul class="submenu">
                                @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                    <li class="menu-item">
                                        <button class="dropdown-btn">التقارير اليومية</button>
                                        <ul class="submenu">
                                            @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_create == 1)
                                                <li><a href="{{ route('monitoring.create') }}">إضافة تقرير يومي</a></li>
                                            @endif
                                            <li><a href="{{ route('monitoring.index') }}">قائمة التقارير اليومية</a></li>
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>
        @endif

        <li class="menu-item">
            <button class="dropdown-btn">  التقارير </button>
            <ul class="submenu">
                @if(Auth::user()->sectionsPermissions('daily_monitoring'))
                    <li class="menu-item">
                        <button class="dropdown-btn">تقارير الرقابة اليومية </button>
                        <ul class="submenu">
                                <li class="menu-item">
                                    <button class="dropdown-btn">التقارير اليومية</button>
                                    <ul class="submenu">
                                        @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_show == 1)
                                            <li><a href="{{ route('reportMonitoring.index') }}"> جميع التقارير </a></li>
                                        @endif
                                    </ul>
                                </li>


                        </ul>
                    </li>
                @endif
                @if(Auth::user()->sectionsPermissions('5-insertions'))
                        <li class="menu-item">
                            <button class="dropdown-btn">تقارير المهام  </button>
                            <ul class="submenu">
                                <li class="menu-item">
                                    <button class="dropdown-btn"> تقارير المهام </button>
                                    <ul class="submenu">
                                        <li><a href="{{ route('reportTasks.index') }}"> جميع المهام </a></li>
                                        <li><a href="{{ route('reportTasks.byEmployeeSummary') }}">  مهام الموظفين </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->isAdmin() and Auth::user()->sectionsPermissions('8-operations-supervises'))
                        <li class="menu-item">
                            <button class="dropdown-btn">تقارير العملاء   </button>
                            <ul class="submenu">
                                <li class="menu-item">


                                     <a href="{{ route('reportSupervisors.index') }}"> تقارير عمليات الاشراف </a>

                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('customerRequests.index') }}"> تقارير طلبات العملاء  </a>
                                </li>


                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->sectionsPermissions('1-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">تقارير المخزون   </button>
                            <ul class="submenu">

                                <li><a href="{{ route('storeMovements.ReportIndex') }}"  target="_blank">  حركات المخزون </a></li>

                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->sectionsPermissions('2-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">تقارير الاصول   </button>
                            <ul class="submenu">

                                <li><a href="{{ route('assetsMovements.byOperation') }}">  حركات الاصول </a></li>

                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->isAdmin())
                        <li><a href="{{ route('ratingReport.index') }}">  تقارير البلاغات </a></li>
                    @endif
                @if(Auth::user()->sectionsPermissions('4-operations'))
                        <li class="menu-item">
                            <button class="dropdown-btn">تقارير العمالة   </button>
                            <ul class="submenu">

                                <li><a href="{{ route('ratingReport.byOperationDetail') }}">  تقييمات الموظفين </a></li>

                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->isAdmin() )
                                <li><a href="{{ route('SystemMovement.index') }}">  حرحكات النظام  </a></li>

                    @endif
            </ul>
        </li>











    </ul>

    <script src="{{ asset('js/sidebar.js') }}"></script>

</aside>
