<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            المشتريات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر إضافة --}}
        @if(Auth::user()->isAdmin() || Auth::user()->is_purchase() > 1)
            <div class="add btn">
                <a href="{{ route('purchase_purchase.create') }}">
                    إنشاء فاتورة جديدة <i class="fa-solid fa-plus"></i>
                </a>
            </div>
        @endif

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>الملاحظة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>

                            {{-- التاريخ --}}
                            <td>{{ $purchase->created_at->format('Y-m-d') }}</td>

                            {{-- الملاحظة --}}
                            <td>{{ $purchase->note ?? '--' }}</td>

                            {{-- العمليات --}}
                            <td>
                                <div class="actions">

                                    {{-- اعتماد --}}
                                    @if(Auth::user()->isAdmin())
                                        <form method="POST"
                                              action="{{ route('purchase_purchase.confirm', $purchase->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            <button class="btn btn-success">اعتماد</button>
                                        </form>
                                    @endif

                                    {{-- عرض --}}
                                    <a href="{{ route('purchase_purchase.show', $purchase->id) }}"
                                       class="btn btn-primary">عرض</a>

                                    {{-- تعديل --}}
                                    @if(Auth::user()->isAdmin() || Auth::user()->is_purchase() > 1)
                                        <a href="{{ route('purchase_purchase.edit', $purchase->id) }}"
                                           class="btn btn-worn">تعديل</a>
                                    @endif

                                    {{-- استلام --}}
                                    @if(Auth::user()->isAdmin() || Auth::user()->is_intake() > 1)
                                        <a href="{{ route('intake.buy', $purchase->id) }}"
                                           class="btn btn-primary">استلام</a>
                                    @endif

                                    {{-- حذف --}}
                                    @if(Auth::user()->isAdmin() || Auth::user()->is_purchase() > 1)
                                        <form method="POST"
                                              action="{{ route('purchase_purchase.destroy', $purchase->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $purchase->id }})">
                                                حذف
                                            </button>  </form>
                                    @endif

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
            {{ $purchases->links() }}
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
