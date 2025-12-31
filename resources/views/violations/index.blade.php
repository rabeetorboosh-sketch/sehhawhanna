<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            سجل المخالفات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('violations.create') }}">إضافة مخالفة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>بواسطة</th>
                        <th>نوع المخالفة</th>
                        <th>أُرسلت إلى</th>
                        <th>التاريخ</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($violations as $violation)
                        <tr>
                            <td>{{ $violation->id }}</td>
                            <td>{{ $violation->employee->name ?? '-' }}</td>
                            <td>{{ $violation->creator->name ?? '-' }}</td>
                            <td>
                                <span class="badge">
                                    {{ $violation->violations_type }} (#{{ $violation->violation_id }})
                                </span>
                            </td>
                            <td>
                                @if(!empty($violation->sent_to))
                                    @foreach($violation->sent_to as $target)
                                        <span style="font-size: 0.8rem; background: #eee; padding: 2px 5px; border-radius: 4px; margin: 1px; display: inline-block;">
                                            {{ strtoupper($target) }}
                                        </span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $violation->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('violations.show', $violation->id) }}" class="btn btn-primary">عرض</a>

                                    {{-- تفعيل الصلاحيات بناءً على نظامك --}}
                                    @if(Auth::user()->permissions('violations')?->can_update == 1 || Auth::user()->hasRole('admin'))
                                        <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif

                                    @if(Auth::user()->permissions('violations')?->can_delete == 1 || Auth::user()->hasRole('admin'))
                                        <form id="delete-form-{{ $violation->id }}" action="{{ route('violations.destroy', $violation->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $violation->id }})">حذف</button>
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

        {{-- عرض روابط الترقيم في حال كنت تستخدم Paginate --}}
        <div class="mt-4">
            {{ $violations->links() }}
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>

    {{-- إضافة تنبيه الحذف في حال لم يكن موجوداً في ملف table.js --}}
    <script>
        function confirmDelete(id) {
            if (confirm('هل أنت متأكد من حذف هذه المخالفة؟')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
