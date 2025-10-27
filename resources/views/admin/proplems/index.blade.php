<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'انواع المشاكل ' }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table class="table" id="orders">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العميل</th>
                                    <th>الحالة</th>
                                    <th>الإجمالي</th>
                                    <th>تاريخ</th>
                                    <th>أكشن</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td data-label="#">1023</td>
                                    <td data-label="العميل">شركة الندى</td>
                                    <td data-label="الحالة"><span class="badge badge--success">مدفوع</span></td>
                                    <td data-label="الإجمالي">2,350 ر.س</td>
                                    <td data-label="تاريخ">2025-08-28</td>
                                    <td data-label="أكشن">
                                        <button class="btn btn--primary">عرض</button>
                                        <button class="btn btn--ghost">حذف</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="#">1023</td>
                                    <td data-label="العميل">شركة الندى</td>
                                    <td data-label="الحالة"><span class="badge badge--success">مدفوع</span></td>
                                    <td data-label="الإجمالي">2,350 ر.س</td>
                                    <td data-label="تاريخ">2025-08-28</td>
                                    <td data-label="أكشن">
                                        <button class="btn btn--primary">عرض</button>
                                        <button class="btn btn--ghost">حذف</button>
                                    </td>
                                </tr>        <tr>
                                    <td data-label="#">1023</td>
                                    <td data-label="العميل">شركة الندى</td>
                                    <td data-label="الحالة"><span class="badge badge--success">مدفوع</span></td>
                                    <td data-label="الإجمالي">2,350 ر.س</td>
                                    <td data-label="تاريخ">2025-08-28</td>
                                    <td data-label="أكشن">
                                        <button class="btn btn--primary">عرض</button>
                                        <button class="btn btn--ghost">حذف</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="#">1023</td>
                                    <td data-label="العميل">شركة الندى</td>
                                    <td data-label="الحالة"><span class="badge badge--success">مدفوع</span></td>
                                    <td data-label="الإجمالي">2,350 ر.س</td>
                                    <td data-label="تاريخ">2025-08-28</td>
                                    <td data-label="أكشن">
                                        <button class="btn btn--primary">عرض</button>
                                        <button class="btn btn--ghost">حذف</button>
                                    </td>
                                </tr>
                                <tr >
                                    <td data-label="#">1024</td>
                                    <td data-label="العميل">متجر نور</td>
                                    <td data-label="الحالة"><span class="badge badge--warning">معلق</span></td>
                                    <td data-label="الإجمالي">980 ر.س</td>
                                    <td data-label="تاريخ">2025-08-30</td>
                                    <td data-label="أكشن">
                                        <button class="btn btn--primary">عرض</button>
                                        <button class="btn btn--ghost">حذف</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
