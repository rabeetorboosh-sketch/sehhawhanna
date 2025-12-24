<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            استلام طلب شراء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/movements.css') }}">

    {{-- ===================== نسخة الكمبيوتر ===================== --}}
    <div class="forPC bg-white p-6 rounded-lg shadow-md">

        <h1 class="text-2xl font-bold mb-4">استلام طلب شراء <i class="fas fa-truck-loading"></i></h1>

        <form method="POST" action="{{ route('purchase_purchase.store') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $purchaseRequest->id }}">

            <div class="sections-bar">
                @foreach($sections as $section)
                    <div class="section-holder mb-6">
                        <div class="section-header font-bold text-lg mb-2">
                            {{ $section->name }} <i class="fas fa-list"></i>
                        </div>

                        @foreach($groups->where('pur_group_id', $section->id) as $group)
                            @php
                                $itemsGroup = $purchaseRequest->requestItems->filter(fn($ri) => $ri->item->pur_sup_group_id == $group->id);
                            @endphp

                            @if($itemsGroup->count())
                                <div class="group-holder mb-4">
                                    <div class="group-header font-semibold mb-2">
                                        {{ $group->name }} <i class="fas fa-layer-group"></i>
                                    </div>

                                    <div class="searchable-select">
                                        @foreach($itemsGroup as $option)
                                            @php
                                                $itemcount = $preItems->clone()->where('pur_item_id', $option->item->id)->first();
                                            @endphp

                                            <div class="grp{{ $option->item->pur_sup_group_id }} item-container flex gap-2 mb-1">
                                                <input value="{{ $option->item->name }}" class="product-name flex-1" style="border:none;" disabled>
                                                <input type="hidden" name="items[{{ $option->item->id }}][item_id]" value="{{ $option->item->id }}">

                                                <input type="text" value="{{ $option->unit?->name }}" class="w-1/5" readonly>
                                                <input type="hidden" name="items[{{ $option->item->id }}][unit_id]" value="{{ $option->unit?->id }}">

                                                <input type="number" value="{{ $option->request_count }}" class="w-1/5" readonly placeholder="الكمية المطلوبة">
                                                <input type="number" value="{{ $itemcount->purchase_count ?? '' }}" class="w-1/5" readonly placeholder="المستلمة مسبقاً">

                                                <input type="number" name="items[{{ $option->item->id }}][purchase_count]" value="{{ old('items.' . $option->item->id . '.purchase_count') }}" class="w-1/5" placeholder="الكمية الجديدة">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" id="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchaseRequest->note) }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                    <i class="fas fa-save"></i> حفظ
                </button>
                <a href="{{ route('purchase_purchase.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
            </div>
        </form>
    </div>

    {{-- ===================== نسخة الجوال ===================== --}}
    <div class="forPhone bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">استلام طلب شراء <i class="fas fa-truck-loading"></i></h1>

        <div class="sections-bar mb-4">
            <div class="sections-container mb-2">
                @foreach($sections as $section)
                    <button class="section-lbl" value="{{ $section->id }}">
                        {{ $section->section_name }} <i class="fas fa-list"></i>
                    </button>
                @endforeach
            </div>

            <div class="groups-container mb-4">
                @foreach($groups as $group)
                    <button class="group-lbl sec{{ $group->section_id }}" value="{{ $group->id }}">
                        {{ $group->group_name }} <i class="fas fa-layer-group"></i>
                    </button>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('purchase_purchase.store') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $purchaseRequest->id }}">

            <div id="items-list">
                @foreach($purchaseRequest->requestItems as $option)
                    @php
                        $itemcount = $preItems->clone()->where('pur_item_id', $option->item->id)->first();
                    @endphp

                    <div class="grp{{ $option->item->pur_group_id }} item-container buy flex gap-2 mb-2">
                        <input value="{{ $option->item->name }}" class="product-name flex-1" disabled>
                        <input type="hidden" name="items[{{ $option->item->id }}][item_id]" value="{{ $option->item->id }}">

                        <input type="text" value="{{ $option->unit?->name }}" class="w-1/4" readonly>
                        <input type="hidden" name="items[{{ $option->item->id }}][unit_id]" value="{{ $option->unit?->id }}">

                        <input type="number" value="{{ $option->request_count }}" class="w-1/4" readonly placeholder="الكمية المطلوبة">
                        <input type="number" value="{{ $itemcount->purchase_count ?? '' }}" class="w-1/4" readonly placeholder="المستلمة مسبقاً">

                        <input type="number" name="items[{{ $option->item->id }}][purchase_count]" value="{{ old('items.' . $option->item->id . '.purchase_count') }}" class="w-1/4" placeholder="الكمية الجديدة">
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" id="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchaseRequest->note) }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                    <i class="fas fa-save"></i> حفظ
                </button>
                <a href="{{ route('purchase_purchase.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/movements.js') }}"></script>
</x-app-layout>

