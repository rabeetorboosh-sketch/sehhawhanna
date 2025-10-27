<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل حركة مخزون ({{ $movement->name }})
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('css/movements.css')}}">

    <div class="forPC bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('storeMovements.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="sections-bar">
                <div class="groups-container">
                    @foreach($groups as $group)
                        @php
                            $productsGroup = $products->filter(fn($product) => $product->item->sub_group_id == $group->id);
                        @endphp

                        <div class="mb-4">
                            <div class="group-header">
                                {{$group->name}} <i class="fas fa-layer-group"></i>
                            </div>
                            <div id="product-fields">
                                <div class="flex items-center mb-4">
                                    <div class="searchable-select">
                                        <input type="hidden" name="direction" value="{{$movement->direction}}">
                                        <input type="hidden" name="movement_id" value="{{$movement->id}}">

                                        @foreach($productsGroup as $pro)
                                            @php
                                                $oldCount = old("product_id.$pro->id.item_count");
                                                $savedCount = $transaction->items->firstWhere('product_id',$pro->id)?->count;
                                            @endphp
                                            <div class="S grp{{$pro->item->sub_group_id}} item-container" style="margin-bottom: 1px; display:flex">
                                                <input value="{{ $pro->item->name }}" class="product-name" style="border:none" disabled>
                                                <input name="product_id[{{$pro->id}}][id]" value="{{ $pro->id }}" type="hidden">

                                                <select name="product_id[{{$pro->id}}][unit]" class="unit-select">
                                                    @foreach($pro->item->units as $unit)
                                                        <option value="{{$unit->id}}"
                                                            {{ old("product_id.$pro->id.unit", $transaction->items->firstWhere('product_id',$pro->id)?->product_unit_id) == $unit->id ? 'selected' : '' }}>
                                                            {{$unit->unit?->name}}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="flex space-x-4">
                                                    <input type="number" name="product_id[{{$pro->id}}][item_count]"
                                                           value="{{ $oldCount ?? $savedCount }}"
                                                           class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count"
                                                           placeholder="الكمية">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="summaryPC">الإجمالي: 0</div>

            <input type="hidden" name="stores_number" value="{{$movement->stores_number}}">
            @if($movement->stores_number >1)
                <div class="mb-4" style="display:inline-block;">
                    <label for="store_id">{{$movement->direction==1?'من':'الى'}}</label>
                    <select name="store_id" id="store_id" class="emp-input store-select" required>
                        <option value="">اختر مستودعا</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}"
                                {{  ($transaction->movement->direction==1?$transaction->from_store_id :$transaction->to_store_id) == $store->id    ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <div class="mb-4" style="display:inline-block;">
                    <label for="user_id_pc">{{$movement->direction==0?'من':'الى'}}</label>
                    <select name="employee_id" id="user_id_pc" class="emp-input employee-select" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ old('employee_id', $transaction->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <select name="employee_store_id" id="employee_store_id_pc" class="emp-input" required>
                    <option value="">اختر مستودعا</option>
                    @foreach($stores as $store)

                        <option value="{{ $store->id }}"
                            {{  ($transaction->movement->direction==0?$transaction->from_store_id :$transaction->to_store_id) == $store->id    ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $transaction->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">الصورة</label>
                @if($transaction->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$transaction->image) }}" width="120" class="rounded shadow">
                    </div>
                @endif
                <input type="file" accept="image/*" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <button type="submit" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-save"></i> تحديث
            </button>
            <a href="{{ route('storeMovements.index') }}" class="btn btn-outline" style="margin-left:10px;">
                <i class="fas fa-arrow-left"></i> إلغاء
            </a>
        </form>
    </div>
    <div class="forPhone bg-white p-6 rounded-lg shadow-md">
        <div class="sections-bar">
            <div class="sections-container">
                @foreach($sections as $section)
                    <button class="section-lbl" value="{{$section->id}}">
                        {{$section->name}} <i class="fas fa-list"></i>
                    </button>
                @endforeach
            </div>

            <div class="groups-container">
                @foreach($groups as $group)
                    <button class="group-lbl sec{{$group->main_group_id}}" value="{{$group->id}}">
                        {{$group->name}} <i class="fas fa-layer-group"></i>
                    </button>
                @endforeach
            </div>
        </div>

        <form class="smart-form" action="{{ route('storeMovements.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <input type="hidden" name="movement_id" value="{{$movement->id}}">
                <input type="hidden" name="direction" value="{{$movement->direction}}">
                <label for="products" class="block text-sm font-medium text-gray-700 productlpl">اختر المنتجات</label>
                <div id="product-fields">
                    <div class="flex items-center mb-4" id="product-field-1">
                        <div class="searchable-select">
                            @foreach($products as $pro)
                                @php
                                    $oldCount = old("product_id.$pro->id.item_count");
                                    $savedCount = $transaction->items->firstWhere('product_id',$pro->id)?->count;
                                @endphp
                                <div class="PH grp{{$pro->item?->sub_group_id}} item-container" style="margin-bottom: 1px;">
                                    <input value="{{ $pro->item?->name }}" class="product-name" style="border:none" disabled>
                                    <input name="product_id[{{ $pro->id }}][id]" value="{{ $pro->id }}" type="hidden">

                                    <select name="product_id[{{ $pro->id }}][unit]" class="unit-select">
                                        @foreach($pro->item->units as $unit)
                                            <option value="{{$unit->id}}"
                                                {{ old("product_id.$pro->id.unit", $transaction->items->firstWhere('product_id',$pro->id)?->product_unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{$unit->unit?->name}}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="flex space-x-4">
                                        <input type="number" name="product_id[{{$pro->id}}][item_count]"
                                               value="{{ $oldCount ?? $savedCount }}"
                                               class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count"
                                               placeholder="الكمية">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="summaryPhone">الإجمالي: 0</div>
            </div>

            <input type="hidden" name="stores_number" value="{{$movement->stores_number}}">
            @if($movement->stores_number >1)
                <div class="mb-4" style="display:inline-block;">
                    <label for="store_id">{{$movement->direction==1?'من':'الى'}}</label>
                    <select name="store_id" id="store_id_phone" class="emp-input store-select" required>
                        <option value="">اختر مستودعا</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}"
                                {{ ($transaction->movement->direction==1?$transaction->from_store_id :$transaction->to_store_id) == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="row-2">
                <div class="mb-4 form-group" style="display:inline-block;">
                    <label for="user_id_phone">{{$movement->direction==0?'من':'الى'}}</label>
                    <select name="employee_id" id="user_id_phone" class="emp-input employee-select" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ old('employee_id', $transaction->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <select name="employee_store_id" id="employee_store_id_phone" class="emp-input " required>
                    <option value="">اختر مستودعا</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}"
                            {{ ($transaction->movement->direction==0?$transaction->from_store_id :$transaction->to_store_id) == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $transaction->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">الصورة</label>
                @if($transaction->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$transaction->image) }}" width="120" class="rounded shadow">
                    </div>
                @endif
                <input type="file" accept="image/*" capture="image" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="row-3">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> تحديث
                </button>
                <a href="{{ route('storeMovements.index') }}" class="btn btn-worn" style="margin-left:10px;">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
    <script src="{{asset('js/movements.js')}}"></script>
</x-app-layout>
