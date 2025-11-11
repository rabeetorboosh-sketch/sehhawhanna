@extends('admin.system_movements.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">
    @php
        use App\Models\Product;
        use App\Models\ItemUnit;
        use App\Models\Unit;
        use App\Models\User;

        // ترجمة الموديلات
        $Translations = [
            'products'      => 'الأصناف',
            'units'         => 'الوحدات',
            'stores'        => 'المخازن',
            'controlUnits'  => 'وحدات الرقابة',
            'customers'     => 'العملاء',
            'suppliers'     => 'الموردين',
            'employee_id'   => 'الموظف',
            'tasks'         => 'المهام',
            'departments'   => 'الأقسام',
            'groups'        => 'المجموعات',
            'users'         => 'المستخدمين',
            'reports'       => 'البلاغات',
            'permissions'   => 'الصلاحيات',
            'packages'      => 'الحزم',
            'maintenance_request' => 'طلبات الصيانة',
            'maintenance'   => 'عمليات الصيانة',
            'movements'     => 'نقل الأصول',
            'monitoring'    => 'الرقابة اليومية',
            'assignments'   => 'إسناد المهام',
            'supervises'    => 'الإشراف',
            'receipts'      => 'استلام المهام',
            'branches'      => 'الفروع',
            'issues'        => 'أنواع المشاكل',
            'control_units' => 'وحدات الرقابة',
            'main_groups'   => 'المجموعات الرئيسية',
            'assets'        => 'الأصول',
            'sub_groups'    => 'المجموعات الفرعية',
            'daily_monitoring' => 'الرقابة اليومية',
            'operations'    => 'العمليات',
            'myTask'        => 'مهامي اليومية',
            'exp'           => 'التوالف',
            'ret'           => 'المرتجعات',
            'load'          => 'التحميل',
            'ratingUnits'   => 'وحدات التقييم',
            'ratings'       => 'التقييم',
            'sales_routs'   => 'الخطوط',
            'customersRequests' => 'طلبات العملاء',
            'from_store_id' => 'من مخزن',
            'to_store_id'   => 'إلى مخزن',
            'product_update'=> 'تحديث منتج',
            'description'   => 'الوصف',
        ];
    @endphp

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الحقل</th>
                    <th>القيمة قبل التغيير</th>
                    <th>القيمة بعد التغيير</th>
                    <th>رقم الفاتورة</th>
                    <th>نوع الفاتورة</th>
                    <th>اسم المستخدم</th>
                    <th>تاريخ التعديل</th>
                </tr>
                </thead>
                <tbody>
                @php
                   // دالة صغيرة لتحليل نص التحديث
                    function parseUpdateValue($value) {
                        $data = [];
                        $parts = explode(',', $value ?? '');
                        foreach ($parts as $part) {
                            if (str_contains($part, ':')) {
                                [$key, $val] = array_map('trim', explode(':', $part));
                                $data[$key] = $val;
                            }
                        }
                        return $data;
                    }

                    // دالة لجلب اسم الصنف والوحدة
                    function formatProductUpdate($value) {
                        $parsed = parseUpdateValue($value);
                        if (empty($parsed)) return '—';

                        $productName = null;
                        $unitName = null;

                        if (!empty($parsed['Product'])) {
                            $product = Product::find($parsed['Product']);
                            $productName = $product?->name ?? $parsed['Product'];
                        }

                        if (!empty($parsed['Unit'])) {
                            $itemUnit = ItemUnit::find($parsed['Unit']);
                            $unitName = $itemUnit?->unit?->name ?? $parsed['Unit'];
                        }

                        $count = $parsed['Count'] ?? '—';

                        return "الصنف: {$productName} | الوحدة: {$unitName} | الكمية: {$count}";
                    }
                @endphp
                @forelse ($movements as $index => $movement)

                 @php(   $user = User::find($movement->user_id))


                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $Translations[$movement->field_name] ?? $movement->field_name }}</td>

                        <td>
                            @if($movement->field_name == 'product_update')
                                {{ formatProductUpdate($movement->old_value) }}
                            @else
                                {{ $movement->old_value ?? '—' }}
                            @endif
                        </td>

                        <td>
                            @if($movement->field_name == 'product_update')
                                {{ formatProductUpdate($movement->new_value) }}
                            @else
                                {{ $movement->new_value ?? '—' }}
                            @endif
                        </td>

                        <td>{{ $movement->invoice_id ?? '—' }}</td>
                        <td>{{ $movement->invoice_type ?? '—' }}</td>
                        <td>{{ $user->name ?? '—' }}</td>
                        <td>{{ $movement->modified_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">لا توجد حركات مسجلة</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- عدد السجلات --}}
    <div class="summary">
        <div class="num">
            عدد السجلات: <strong>{{ $movements->count() }}</strong>
        </div>
    </div>

    {{-- ترقيم الصفحات --}}
    <div class="pagination">
        @if ($movements->onFirstPage())
            <span class="disabled">السابق</span>
        @else
            <a href="{{ $movements->previousPageUrl() }}" rel="prev">السابق</a>
        @endif

        @foreach ($movements->getUrlRange(1, $movements->lastPage()) as $page => $url)
            @if ($page == $movements->currentPage())
                <a class="active">{{ $page }}</a>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($movements->hasMorePages())
            <a href="{{ $movements->nextPageUrl() }}" rel="next">التالي</a>
        @else
            <span class="disabled">التالي</span>
        @endif
    </div>
@endsection
