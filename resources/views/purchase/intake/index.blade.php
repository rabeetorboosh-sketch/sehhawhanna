<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الاستلامات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر إضافة --}}
        @if(Auth::user()->permissions('pur-operations-intake')?->can_creata == 1)
            <div class="add btn">
                <a href="{{ route('intake.create') }}">
                    إنشاء استلام <i class="fa-solid fa-plus"></i>
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
                    @foreach($purchaseIntakes as $purchaseIntake)
                        <tr>
                            <td>{{ $purchaseIntake->id }}</td>

                            {{-- التاريخ --}}
                            <td>{{ $purchaseIntake->created_at->format('Y-m-d') }}</td>

                            {{-- الملاحظة --}}
                            <td>{{ $purchaseIntake->note ?? '--' }}</td>

                            {{-- العمليات --}}
                            <td>
                                <div class="actions">

                                    {{-- اعتماد --}}
                                    @if(Auth::user()->permissions('pur-operations-intake')?->can_approve== 1)
                                        <form method="POST"
                                              action="{{ route('intake.confirm', $purchaseIntake->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            <button class="btn btn-success">اعتماد</button>
                                        </form>
                                    @endif

                                    {{-- عرض --}}
                                    <a href="{{ route('intake.show', $purchaseIntake->id) }}"
                                       class="btn btn-primary">عرض</a>

                                    {{-- تعديل --}}
                                    @if(Auth::user()->permissions('pur-operations-intake')?->can_edit == 1)
                                        <a href="{{ route('intake.edit', $purchaseIntake->id) }}"
                                           class="btn btn-worn">تعديل</a>
                                    @endif

                                    {{-- تحميل --}}
{{--                                    @if(Auth::user()->isAdmin() || Auth::user()->is_load() > 1)--}}
{{--                                        <a href="{{ route('load.buy', $purchaseIntake->id) }}"--}}
{{--                                           class="btn btn-primary">تحميل</a>--}}
{{--                                    @endif--}}

                                    {{-- حذف --}}
                                    @if(Auth::user()->permissions('pur-operations-intake')?->can_delete == 1)
                                        <form method="POST"
                                              action="{{ route('intake.destroy', $purchaseIntake->id) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                حذف
                                            </button>
                                        </form>
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
            {{ $purchaseIntakes->links() }}
        </div>

    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
