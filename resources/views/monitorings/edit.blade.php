<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل الرقابة اليومية
        </h2>
    </x-slot>
    @if($isAnotherDay)
        <script>
            if(confirm("  هذا التقرير يتبع يوما اخر  هل تريد اكماله على اية حال  ")) {
                // المستخدم ضغط موافق، يكمل العملية
            } else {
                // المستخدم ضغط إلغاء، نرجعه للصفحة السابقة أو لصفحة التقارير
                window.location.href = "{{ route('monitoring.index') }}";
            }
        </script>
    @endif
    <link rel="stylesheet" href="{{ asset('css/monitoring.css') }}">

    <div class="sections-bar">
        <div class="sections-container">
            @foreach($departments as $section)
                <button class="section-lbl btn btn-primary" value="{{$section->id}}">
                    {{$section->name}} <i class="fas fa-list"></i>
                </button>
            @endforeach
        </div>
    </div>
    <div class="main-group-bar">
        <div class="sections-container">
            @foreach($mainGroups as $mainGroup)
                <button class=" maingrp{{$mainGroup->department_id??''}} mainGroup-lbl btn btn-secondary" value="{{$mainGroup->id}}">
                    {{$mainGroup->name}}      <i class="fas fa-list"></i>
                </button>
            @endforeach
        </div>
    </div>
    <div class="sub-group-bar">
        <div class="sections-container">
            @foreach($subGroups as $subGroup)
                <button class="subgrp{{$subGroup->main_group_id??''}}  subGroup-lbl btn btn-worn" value="{{$subGroup->id}}">
                    {{$subGroup->name}}      <i class="fas fa-list"></i>
                </button>
            @endforeach
        </div>
    </div>
    <form action="{{ route('monitoring.update', $monitoring->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="products" class="block text-sm font-medium text-gray-700 productlpl">الاقسام</label>
            <div id="product-fields">
                <div class="flex items-center mb-4" id="product-field-1" style="width: 100%">
                    <div class="searchable-select">
                        @foreach($controlUnits as $pro)
                            @php
                                $existingItems = $monitoring->items->where('control_unit_id', $pro->id)->pluck('item_id')->toArray();
                                $existingCauser = $monitoring->items->where('control_unit_id', $pro->id)->first()?->causer_id;
                                $existingDescription = $monitoring->items->where('control_unit_id', $pro->id)->first()?->description;
                                $existingCorrect = $monitoring->items->where('control_unit_id', $pro->id)->first()?->is_correct;
                            @endphp

                            <div class="grp{{$pro->department_id}}  minGrb{{$pro->main_group_id}} subGroup{{$pro->sub_group_id}} item-container">
                                <input value="{{ $pro->name }}" class="product-name" style=" border: none" disabled>
                                <input name="item_id[]" value="{{ $pro->id }}" class="vlue-lbl">

                                <div class="flex space-x-4">
                                    <label>جيد</label>
                                    <input type="checkbox" data-text="{{$pro->id}}" name="is_correct[{{$pro->id}}]" value="1"
                                           class="correct"
                                        {{ $existingCorrect ? 'checked' : '' }}>
                                </div>

                                <textarea name="issue_text[{{ $pro->id }}]" class="txt{{$pro->id}}">{{ $existingDescription }}</textarea>

                                <select name="causer_id[{{ $pro->id }}]" class="cuser{{$pro->id}} select-causer">
                                    <option value=""> اختر متسبب</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $existingCauser == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->item->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="btn btn-secondary items-add" data-text="{{$pro->id}}"> اضافة بنود </span>

                                <div class="items itm{{$pro->id}}">
                                    <div class="filters-grid">
                                        <div>
                                            <select class="department-chose" disabled>
                                                <option value="">اختر القسم</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <select class="mainGroup-chose">
                                                <option value="">اختر المجموعة الرئيسية</option>
                                                @foreach($mainGroups as $mainGroup)
                                                    <option value="{{ $mainGroup->id }}" data-section="{{ $mainGroup->department_id }}">
                                                        {{ $mainGroup->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <select class="supGroup-chose">
                                                <option value="">اختر المجموعة الفرعية</option>
                                                @foreach($subGroups as $subGroup)
                                                    <option value="{{ $subGroup->id }}" data-main-group="{{ $subGroup->main_group_id }}">
                                                        {{ $subGroup->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <input type="text" class="search-items" placeholder="اكتب اسم الصنف...">
                                        </div>
                                    </div>

                                    <div class="items-list">
                                        @foreach($items as $itm)
                                            <div class="item-card"
                                                 data-department="{{ $itm->department_id }}"
                                                 data-main-group="{{ $itm->main_group_id }}"
                                                 data-sub-group="{{ $itm->sub_group_id }}"
                                                 data-name="{{ strtolower($itm->name) }}">

                                                <label>
                                                    <input type="checkbox" value="{{ $itm->id }}" class="itemsCheck" name="items[{{ $pro->id }}][]"
                                                        {{ in_array($itm->id, $existingItems) ? 'checked' : '' }}>
                                                    <span>{{ $itm->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="save-container">
                                        <span class="items-save" data-text="{{$pro->id}}">حفظ</span>
                                    </div>
                                </div>

                                {{-- الصور --}}
{{--                                @if($pro->has_photos == 1)--}}
{{--                                    <div style="margin-top: 1px;">--}}
{{--                                        <input type="file" accept="image/*" name="image[{{ $pro->id }}]" id="image-{{$pro->id}}" class="hidden">--}}
{{--                                        <label for="image-{{$pro->id}}"                                 style="--}}
{{--            display: inline-block;--}}
{{--            padding: 5px 5px;--}}
{{--            background-color: #494c50;--}}
{{--            color: white;--}}
{{--            border-radius: 5px;--}}
{{--            cursor: pointer;--}}
{{--            font-size: 8px;--}}
{{--        "><i class="fas fa-camera"></i> تعديل صورة</label>--}}
{{--                                    </div>--}}
{{--                                @elseif($pro->has_photos == 2)--}}
{{--                                    <div id="multi-images-{{$pro->id}}" style="margin-top: 1px;">--}}
{{--                                        <input type="file" accept="image/*" name="images[{{ $pro->id }}][]" id="images-{{$pro->id}}" class="hidden" multiple>--}}
{{--                                        <label for="images-{{$pro->id}}"--}}
{{--                                        style="--}}
{{--            display: inline-block;--}}
{{--            padding: 5px 5px;--}}
{{--            background-color: #494c50;--}}
{{--            color: white;--}}
{{--            border-radius: 5px;--}}
{{--            cursor: pointer;--}}
{{--            font-size: 8px;--}}
{{--        "--}}

{{--                                        ><i class="fas fa-camera"></i> تعديل الصور</label>--}}
{{--                                        <button type="button" onclick="addNewImageField({{$pro->id}})"  style="--}}
{{--            margin-top: 5px;--}}
{{--            padding: 5px 5px;--}}
{{--            background-color: #28a745;--}}
{{--            color: white;--}}
{{--            border-radius: 5px;--}}
{{--            cursor: pointer;--}}
{{--            font-size: 8px;--}}
{{--            border: none;--}}
{{--        ">--}}
{{--                                            <i class="fas fa-plus"></i> إضافة صورة جديدة--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                {{-- الصور --}}
                                @if($pro->has_photos == 1)
                                    @php
                                        $existingMedia = $monitoring->items
                                            ->where('control_unit_id', $pro->id)
                                            ->first()?->media ?? collect();
                                    @endphp

                                    <div style="margin-top: 1px;">
                                        {{-- عرض الصور القديمة --}}
                                        @if($existingMedia->isNotEmpty())
                                            <div class="media-grid">
                                                @foreach($existingMedia as $media)
                                                  <input type="hidden" name="old_images[{{ $pro->id }}][]" value="{{ $media->url }}">
                                                @endforeach
                                            </div>
                                        @endif

                                        <input type="file" accept="image/*" name="image[{{ $pro->id }}]" id="image-{{$pro->id}}" class="hidden">
                                        <label for="image-{{$pro->id}}" style="
            display: inline-block;
            padding: 5px 5px;
            background-color: #494c50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 8px;">
                                            <i class="fas fa-camera"></i> تعديل صورة
                                        </label>
                                    </div>
                                @elseif($pro->has_photos == 2)
                                    @php
                                        $existingMedia = $monitoring->items
                                            ->where('control_unit_id', $pro->id)
                                            ->first()?->media ?? collect();
                                    @endphp

                                    <div id="multi-images-{{$pro->id}}" style="margin-top: 1px;">
                                        {{-- عرض الصور القديمة --}}
                                        @if($existingMedia->isNotEmpty())
                                            <div class="media-grid">
                                                @foreach($existingMedia as $media)
                                                    <input type="hidden" name="old_images[{{ $pro->id }}][]" value="{{ $media->getRawOriginal('url')  }}">
                                                @endforeach
                                            </div>
                                        @endif

                                        <input type="file" accept="image/*" name="images[{{ $pro->id }}][]" id="images-{{$pro->id}}" class="hidden" multiple>
                                        <label for="images-{{$pro->id}}" style="
            display: inline-block;
            padding: 5px 5px;
            background-color: #494c50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 8px;">
                                            <i class="fas fa-camera"></i> تعديل الصور
                                        </label>
                                        <button type="button" onclick="addNewImageField({{$pro->id}})" style="
            margin-top: 5px;
            padding: 5px 5px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 8px;
            border: none;">
                                            <i class="fas fa-plus"></i> إضافة صورة جديدة
                                        </button>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-save">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
    </form>

    <script src="{{ asset('js/monitoring.js') }}"></script>
</x-app-layout>
