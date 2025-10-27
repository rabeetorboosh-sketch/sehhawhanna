<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          صلاحيات المستخدم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('permissions.create') }}">
                إضافة صلاحيات  <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th> حزمة الصلاحيات </th>
                        <th>الوصف</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{$user->packages->first()->name }}</td>
                            <td>{{$user->packages->first()->description }}</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('permissions.show', $user->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('permissions.edit', $user->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('permissions.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{-- في حال تستخدم paginate --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>


</x-app-layout>
