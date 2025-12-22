@extends('layouts.app')

@section('content')

    <div class="forPC bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">تعديل طلب شراء <i class="fas fa-edit"></i></h1>

        <form method="POST" action="{{ route('purchase_requests.update', $purchaseRequest) }}">
            @csrf
            @method('PUT')

            <div class="sections-bar">
                <div class="groups-container">
                    @foreach($groups as $group)
                        @php
                            $itemsGroup = collect($items->all())->where('group_id', $group->id);
                        @endphp
                        <div class="mb-4">
                            <div class="group-header">
                                {{$group->group_name}} <i class="fas fa-layer-group"></i>
                            </div>

                            <div class="flex items-center mb-4" id="item-field-1">
                                <div class="searchable-select">
                                    @foreach($itemsGroup as $item)
                                        @php
                                            $existing = $purchaseRequest->requestItems->firstWhere('item_id', $item->id);
                                        @endphp
                                        <div class="grp{{$item->group_id}} item-container" style="margin-bottom: 1px; display: flex">
                                            <input value="{{ $item->name }}" class="product-name" style="border: none" disabled>
                                            <input name="items[{{$item->id}}][item_id]" value="{{ $item->id }}" class="vlue-lbl">

                                            <select name="items[{{$item->id}}][unit_id]" class="unit-select">
                                                @foreach($item->units as $unit)
                                                    <option value="{{$unit->id}}"
                                                            @if($existing && $existing->unit_id == $unit->id)
                                                                selected
                                                            @elseif(!$existing && $unit->is_default)
                                                                selected
                                                        @endif
                                                    >{{$unit->unit_name}}</option>
                                                @endforeach
                                            </select>

                                            <input type="number" name="items[{{$item->id}}][request_count]" value="{{ $existing ? $existing->request_count : '' }}" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count" placeholder="الكمية">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" id="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchaseRequest->note) }}</textarea>
            </div>

            <button type="submit" class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-save"></i> تحديث
            </button>
            <a href="{{ route('purchase_requests.index') }}" class="btn btn-outline" style="margin-left: 10px;">
                <i class="fas fa-arrow-left"></i> إلغاء
            </a>
        </form>
    </div>

    {{-- نسخة الجوال --}}
    <div class="forPhone bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">تعديل طلب شراء <i class="fas fa-edit"></i></h1>

        <div class="sections-bar">
            <div class="sections-container">
                @foreach($sections as $section)
                    <button class="section-lbl" value="{{$section->id}}">
                        {{$section->section_name}} <i class="fas fa-list"></i>
                    </button>
                @endforeach
            </div>

            <div class="groups-container">
                @foreach($groups as $group)
                    <button class="group-lbl sec{{$group->section_id}}" value="{{$group->id}}">
                        {{$group->group_name}} <i class="fas fa-layer-group"></i>
                    </button>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('purchase_requests.update', $purchaseRequest) }}">
            @csrf
            @method('PUT')

            <label class="block text-sm font-medium text-gray-700">العناصر</label>
            <div id="items-list">
                @foreach($items as $item)
                    @php
                        $existing = $purchaseRequest->requestItems->firstWhere('item_id', $item->id);
                    @endphp
                    <div class="grp{{$item->group_id}} item-container" style="margin-bottom: 1px;">
                        <input value="{{ $item->name }}" class="product-name" disabled>
                        <input name="items[{{$item->id}}][item_id]" value="{{ $item->id }}" class="vlue-lbl">

                        <select name="items[{{$item->id}}][unit_id]" class="unit-select">
                            @foreach($item->units as $unit)
                                <option value="{{$unit->id}}"
                                        @if($existing && $existing->unit_id == $unit->id)
                                            selected
                                        @elseif(!$existing && $unit->is_default)
                                            selected
                                    @endif
                                >{{$unit->unit_name}}</option>
                            @endforeach
                        </select>

                        <input type="number" name="items[{{$item->id}}][request_count]" value="{{ $existing ? $existing->request_count : '' }}" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count" placeholder="الكمية">
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="note" id="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('note', $purchaseRequest->note) }}</textarea>
            </div>

            <button type="submit" class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-save"></i> تحديث
            </button>
            <a href="{{ route('purchase_requests.index') }}" class="btn btn-outline" style="margin-left: 10px;">
                <i class="fas fa-arrow-left"></i> إلغاء
            </a>
        </form>
    </div>

@endsection
