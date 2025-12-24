<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل فاتورة مشتريات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/movements.css') }}">

    {{-- ===================== نسخة الكمبيوتر ===================== --}}
    <div class="forPC bg-white p-6 rounded-lg shadow-md">

        <form method="POST" action="{{ route('purchase_purchase.update', $purchase->id) }}">
            @csrf
            @method('PUT')

            <div class="sections-bar">
                <div class="groups-container">

                    @foreach($groups as $group)
                        @php
                            $itemsGroup = $items->where('pur_sup_group_id', $group->id);
                        @endphp

                        <div class="mb-4">
                            <div class="group-header">
                                {{ $group->name }}
                                <i class="fas fa-layer-group"></i>
                            </div>

                            @foreach($itemsGroup as $item)
                                @php
                                    $old = $oldItems[$item->id] ?? null;
                                @endphp

                                <div class="item-container" style="display:flex;margin-bottom:1px">

                                    <input value="{{ $item->name }}" disabled class="product-name">

                                    <input type="hidden"
                                           name="items[{{ $item->id }}][item_id]"
                                           value="{{ $item->id }}">

                                    <select name="items[{{ $item->id }}][unit_id]" class="unit-select">
                                        @foreach($item->units as $unit)
                                            <option value="{{ $unit->id }}"
                                                @selected(
                                                    $old
                                                        ? $old->pur_unit_id == $unit->id
                                                        : $unit->is_default
                                                )>
                                                {{ $unit->unit->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="number"
                                           name="items[{{ $item->id }}][purchase_count]"
                                           class="count"
                                           value="{{ $old->pur_purchase_count ?? '' }}"
                                           placeholder="الكمية">
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- ملاحظات --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchase->note) }}</textarea>
            </div>

            <div class="row-3">
                <a href="{{ route('purchase_purchase.index') }}" class="btn btn-worn">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> تحديث
                </button>
            </div>

        </form>
    </div>

    {{-- ===================== نسخة الجوال ===================== --}}
    <div class="forPhone bg-white p-6 rounded-lg shadow-md">

        <form method="POST" action="{{ route('purchase_purchase.update', $purchase->id) }}">
            @csrf
            @method('PUT')

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

            <div class="searchable-select">
                @foreach($items as $item)
                    @php
                        $old = $oldItems[$item->id] ?? null;
                    @endphp

                    <div class="PH grp{{ $item->pur_sup_group_id }} item-container">

                        <input value="{{ $item->name }}"
                               class="product-name"
                               style="border:none"
                               disabled>

                        <input type="hidden"
                               name="items[{{ $item->id }}][item_id]"
                               value="{{ $item->id }}">

                        <select name="items[{{ $item->id }}][unit_id]"
                                class="unit-select">
                            @foreach($item->units as $unit)
                                <option value="{{ $unit->id }}"
                                    @selected(
                                        $old
                                            ? $old->pur_unit_id == $unit->id
                                            : $unit->is_default
                                    )>
                                    {{ $unit->unit->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number"
                               name="items[{{ $item->id }}][purchase_count]"
                               value="{{ $old->pur_purchase_count ?? '' }}"
                               class="count"
                               placeholder="الكمية">
                    </div>
                @endforeach
            </div>

            {{-- ملاحظات --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchase->note) }}</textarea>
            </div>

            <div class="row-3">
                <a href="{{ route('purchase_purchase.index') }}" class="btn btn-worn">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> تحديث
                </button>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/movements.js') }}"></script>
</x-app-layout>
