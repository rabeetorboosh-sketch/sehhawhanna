<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            طلبات الشراء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر إضافة --}}

            <div class="add btn">
                <a href="{{ route('purchase_requests.create') }}">
                    إنشاء طلب جديد <i class="fa-solid fa-plus"></i>
                </a>
            </div>


        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>الملاحظة</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>

                            {{-- التاريخ --}}
                            <td>{{ $request->created_at->format('Y-m-d') }}</td>

                            {{-- الملاحظة --}}
                            <td>{{ $request->note ?? '--' }}</td>

                            {{-- حالة الاعتماد --}}
                            <td>
                                @if($request->requestItems->where('is_confirmed', 1)->isNotEmpty())
                                    <span class="status success">معتمد</span>
                                @else
                                    <span class="status pending">غير معتمد</span>
                                @endif
                            </td>

                            {{-- العمليات --}}
                            <td>
                                <div class="actions">

                                    {{-- اعتماد / إلغاء اعتماد --}}
                                    @if(Auth::user()->isAdmin())
                                        @if($request->requestItems->where('is_confirmed', 1)->isNotEmpty())
                                            <form method="POST"
                                                  action="{{ route('purchase_requests.deconfirm', $request->id) }}"
                                                  style="display:inline;">
                                                @csrf
                                                <button class="btn btn-success">إلغاء الاعتماد</button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('purchase_requests.confirm', $request->id) }}"
                                                  style="display:inline;">
                                                @csrf
                                                <button class="btn btn-success">اعتماد</button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- عرض --}}
                                    <a href="{{ route('purchase_requests.show', $request->id) }}"
                                       class="btn btn-primary">عرض</a>

                                    {{-- تعديل --}}

                                        <a href="{{ route('purchase_requests.edit', $request->id) }}"
                                           class="btn btn-worn">تعديل</a>


                                    {{-- شراء --}}
                                    @if(
                                        $request->requestItems->where('is_confirmed', 1)->isNotEmpty()

                                    )
                                        <a href="{{ route('purchase_purchase.buy', $request->id) }}"
                                           class="btn btn-primary">شراء</a>
                                    @endif

                                    {{-- حذف --}}
                                         <form method="POST"
                                              action="{{ route('purchase_requests.destroy', $request->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">حذف</button>
                                        </form>


                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $requests->links() }}
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
