<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            قائمة الحزم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('packages.create') }}">
                إضافة حزمة <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الحزمة</th>
                        <th>الوصف</th>
                        <th>عدد النماذج</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->description }}</td>
                            <td>{{ $package->templates_count }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('packages.show', $package->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $package->id }}" action="{{ route('packages.destroy', $package->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $package->id }})">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{-- في حال تستخدم paginate --}}
                <div class="mt-4">
                    {{ $packages->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>


</x-app-layout>
