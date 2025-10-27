<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            المستخدمين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        @if(Auth::user()->permissions('general-users')?->can_create == 1)
            <div class="add btn">
                <a href="{{ route('users.create') }}">اضافة <i class="fa-solid fa-plus"></i></a>
            </div>
        @endif

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الايميل</th>
                        <th>الصلاحية</th>
                        <th>تاريخ الإنشاء</th>
                        <th>تاريخ التحديث</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->rule =='user'?'مستخدم':'مسؤول' }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>{{ $user->updated_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->permissions('general-users')?->can_update == 1)
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                        @if(Auth::user()->permissions('general-users')?->can_create == 1)
                                        <a href="{{ route('users.permit', $user->id) }}" class="btn btn-primary">تعديل الصلاحية </a>
                                        @endif
                                    @if(Auth::user()->permissions('general-users')?->can_delete == 1)
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">
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
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
    <script>
        function confirmDelete(id) {
            if(confirm('هل أنت متأكد من الحذف؟')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
