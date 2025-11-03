<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض نوع الموظف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات النوع --}}
                <h3 class="section-title">بيانات النوع</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الرقم</span>
                        <span class="info-content">{{ $employeeType->id }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الاسم</span>
                        <span class="info-content">{{ $employeeType->name }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الوصف</span>
                        <span class="info-content">{{ $employeeType->description ?? 'غير محدد' }}</span>
                    </div>
                </div>

                {{-- جدول الموظفين --}}
                <h3 class="section-title">الموظفون المنتمون لهذا النوع</h3>
                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-center border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">الاسم</th>
                            <th class="py-3 px-4 border">الهاتف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($employeeType->employees as $index => $employee)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border">{{ $employee->item->name ?? '-' }}</td>
                                <td class="py-2 px-4 border">{{ $employee->item->employee->phone ?? '-' }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 px-4 border text-gray-500">لا يوجد موظفون لهذا النوع</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <a href="{{ route('employeeType.index') }}" class="btn btn-primary">عودة</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
