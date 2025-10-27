<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة تقرير
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('reports.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row-2">
                <div class="in-form-group">
                    <label>نوع البلاغ</label>
                    <select name="report_type_id" required>
                        @foreach($issueTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
<div class="dv">
    <h1  style=" width : 100%;">فلترة</h1>
    <div class="form-group filter">

        <div class="row-3-static" >
            <div class="in-form-group">
                <label  style="text-wrap: nowrap;">القسم</label>
                <select name="section_id" id="sectionSelect" >
                    @foreach($departments as $department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="in-form-group">
                <label  style="text-wrap: nowrap;" >المجموعة الرئيسية</label>
                <select name="mainGroup" id="mainGroupSelect">
                    <option selected value=""> اختر المجموعة الرئيسية </option>
                    @foreach($mainGroups as $mainGroup)
                        <option value="{{ $mainGroup->id }}" data-section="{{ $mainGroup->department->id }}">
                            {{ $mainGroup->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="in-form-group">
                <label  style="text-wrap: nowrap;">المجموعة الفرعية</label>
                <select name="subGroup" id="subGroupSelect">
                    <option value="" selected> اختر المجموعة الفرعية </option>
                    @foreach($subGroups as $subGroup)
                        <option value="{{ $subGroup->id }}" data-main-group="{{ $subGroup->main_group_id }}">
                            {{ $subGroup->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</div>

                <input type="hidden" name="status" value="0">
            </div>
            <div class="in-form-group" style="border:1px solid gray; border-radius:10px; padding:5px ;display: block">
                <h3>بنود التقرير</h3>
                <div id="items-wrapper">
                    <div class="item-row" data-index="0">
                        <div class="row-3" style="padding: 5px; box-shadow: 0 0 4px gray; border-radius: 5px; position: relative;     margin-top: 5px;">
                            <div class="in-form-group">
                                <label>البند</label>
                                <select name="items[0][item_no]" class="itemsSelect">
                                    <option value="" selected>اختر البند</option>


                                    @foreach($items as $item)
                                        <option value="{{ $item->id . '-' . ($item->department_id ?? '') }}"
                                                data-section="{{$item->department_id}}"
                                                data-main="{{ $item->main_group_id }}"
                                                data-sub="{{ $item->sub_group_id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="in-form-group">
                                <label>الوحدة الرقابية</label>
                                <select name="items[0][control_unit_id]" class="control-unit-select">
                                    <option value="" data-photo="1">     وحدة رقابية يدوية </option>
                                    @foreach($controlUnits as $unit)
                                        <option value="{{ $unit->id }}"
                                                data-photo="{{$unit->has_photos}}"
                                                data-department="{{$unit->department_id}}"
                                                data-main-group="{{$unit->main_group_id}}"
                                                data-sub-group="{{$unit->sub_group_id}}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group user-control-group" style="display: none;">
                                <label>اضف الوحدة رقابية   </label>
                                <input type="text" name="items[0][user_control_unit]">
                            </div>
                            <!-- حقل الصور الأساسي -->
                            <div class="form-group photo-control" style="display: none;">
                                <label> الصور </label>
                                <input type="file" name="items[0][control_unit_photo]">
                            </div>


                            <!-- حقل الصور المتعددة -->
                            <div class="form-group multi-photo-control" style="display: none;">
                                <label> الصور المتعددة </label>
                                <div class="photo-container">
                                    <input type="file" name="items[0][control_unit_photos][]">
                                </div>
                                <button type="button" class="add-photo-btn btn btn-primary">+ إضافة </button>
                            </div>


                            <div class="in-form-group">
                                <label>المتسبب</label>
                                <select name="items[0][causer_id]">
                                    <option value=""> لا يوجد متسبب</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="in-form-group">
                                <label>الوصف</label>
                                <textarea name="items[0][issue_description]"></textarea>
                            </div>

                            <input type="hidden" name="items[0][response_status]" value="0">
                            <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-worn add-item">+</button>
            </div>

            <div class="actions">
                <div   ></div>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/report-form.js') }}"></script>

</x-app-layout>
