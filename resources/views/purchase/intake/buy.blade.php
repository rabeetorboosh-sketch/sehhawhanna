<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            استلام كميات من فاتورة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/movements.css') }}">

    {{-- ===================== نسخة الكمبيوتر ===================== --}}
    <div class="forPC bg-white p-6 rounded-lg shadow-md">

        <form method="POST" action="{{ route('intake.store') }}">
            @csrf
            <input type="hidden" name="pur_purchase_id" value="{{ $purchase->id }}">

            <div class="sections-bar">
                <div class="groups-container">

                    @foreach($groups as $group)
                        @php
                            $itemsGroup = $purchase->purchaseItems
                                ->filter(fn($pi) => $pi->item->pur_sup_group_id == $group->id);
                        @endphp

                        @if($itemsGroup->count())
                            <div class="mb-4">
                                <div class="group-header">
                                    {{ $group->name }}
                                    <i class="fas fa-layer-group"></i>
                                </div>

                                <div class="searchable-select">
                                    @foreach($itemsGroup as $option)
                                        @php
                                            $itemcount = $preItems->clone()
                                                ->where('pur_item_id', $option->item->id)
                                                ->first();
                                        @endphp

                                        <div class="grp{{ $group->id }} buy-item-container"
                                             style="margin-bottom:1px; display:flex; gap:4px">

                                            {{-- اسم الصنف --}}
                                            <input value="{{ $option->item->name }}"
                                                   class="product-name"
                                                   style="border:none"
                                                   disabled>

                                            <input type="hidden"
                                                   name="items[{{ $option->item->id }}][item_id]"
                                                   value="{{ $option->item->id }}">

                                            {{-- الوحدة --}}
                                            <input type="text"
                                                   value="{{ $option->unit?->unit?->name}}"
                                                   readonly>

                                            <input type="hidden"
                                                   name="items[{{ $option->item->id }}][unit_id]"
                                                   value="{{ $option->unit->id }}">

                                            {{-- الكمية المطلوبة --}}
                                            <input type="number"
                                                   value="{{ $option->pur_purchase_count }}"
                                                   class="count"
                                                   readonly
                                                   title="الكمية المطلوبة">

                                            {{-- المستلمة سابقاً --}}
                                            <input type="number"
                                                   value="{{ $itemcount->intake_count ?? 0 }}"
                                                   class="count"
                                                   readonly
                                                   title="المستلمة سابقاً">

                                            {{-- الكمية الجديدة --}}
                                            <input type="number"
                                                   name="items[{{ $option->item->id }}][intake_count]"
                                                   value="{{ old('items.' . $option->item->id . '.intake_count') }}"
                                                   class="count"
                                                   placeholder="الكمية الجديدة">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>

            {{-- ملاحظات --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    {{ old('note', $purchase->note) }}
                </textarea>
            </div>

            <div class="row-3">
                <a href="{{ route('intake.index') }}" class="btn btn-worn">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> حفظ
                </button>
            </div>

        </form>
    </div>

    {{-- ===================== نسخة الجوال ===================== --}}
    <div class="forPhone bg-white p-6 rounded-lg shadow-md">

        <div class="sections-bar">
            <div class="sections-container">
                @foreach($sections as $section)
                    <button class="section-lbl" value="{{ $section->id }}">
                        {{ $section->name }}
                        <i class="fas fa-list"></i>
                    </button>
                @endforeach
            </div>

            <div class="groups-container">
                @foreach($groups as $group)
                    <button class="group-lbl sec{{ $group->pur_group_id }}"
                            value="{{ $group->id }}">
                        {{ $group->name }}
                        <i class="fas fa-layer-group"></i>
                    </button>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('intake.store') }}">
            @csrf
            <input type="hidden" name="pur_purchase_id" value="{{ $purchase->id }}">

            <div class="searchable-select">
                @foreach($purchase->purchaseItems as $option)
                    @php
                        $itemcount = $preItems->clone()
                            ->where('pur_item_id', $option->item->id)
                            ->first();
                    @endphp

                    <div class="PH grp{{ $option->item->pur_sup_group_id }} item-container">

                        <input value="{{ $option->item->name }}"
                               class="product-name"
                               style="border:none"
                               disabled>

                        <input type="hidden"
                               name="items[{{ $option->item->id }}][item_id]"
                               value="{{ $option->item->id }}">

                        <input type="text"
                               value="{{ $option->unit?->unit?->name }}"
                               readonly>

                        <input type="hidden"
                               name="items[{{ $option->item->id }}][unit_id]"
                               value="{{ $option->unit->id }}">

                        <input type="number"
                               value="{{ $option->pur_purchase_count }}"
                               class="count"
                               readonly
                               placeholder="المطلوبة">

                        <input type="number"
                               value="{{ $itemcount->intake_count ?? 0 }}"
                               class="count"
                               readonly
                               placeholder="سابقاً">

                        <input type="number"
                               name="items[{{ $option->item->id }}][intake_count]"
                               value="{{ old('items.' . $option->item->id . '.intake_count') }}"
                               class="count"
                               placeholder="الكمية الجديدة">
                    </div>
                @endforeach
            </div>

            {{-- ملاحظات --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    {{ old('note', $purchase->note) }}
                </textarea>
            </div>

            <div class="row-3">
                <a href="{{ route('intake.index') }}" class="btn btn-worn">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> حفظ
                </button>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/movements.js') }}"></script>
</x-app-layout>
