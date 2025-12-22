{{-- resources/views/admin/asset/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الصنف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assetShow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">


                <h3 class="section-title">بيانات الصنف</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">الصنف</span>
                        <span class="info-content">{{ $product->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الكود</span>
                        <span class="info-content">{{ $product->code ?? '—' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الوحدات</span>
                        <div >
                            <table class="inside-table" >
                                <thead>
                                <tr>

                                    <th>الوحدة </th>
                                    <th>العبوة </th>
                                    <th>  رئيسية </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->units as $unit)
                                        <tr>
                                            <td>{{ $unit->unit?->name ?? '—' }}</td>
                                            <td>{{ $unit->quantity ?? '—' }}</td>
                                            <td> <input type="checkbox" @if($unit->is_main??0==1) checked @endif disabled>   </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                     </div>

                </div>

                <div class="mt-6">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>

            </div>
        </div>
    </div>
    <script  src="{{asset('js/table.js')}}"></script>
</x-app-layout>
