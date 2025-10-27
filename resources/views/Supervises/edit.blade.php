<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل تقرير
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supervisors.css') }}">

    <div class="py-12">
        <form class="smart-form" method="POST" action="{{ route('supervises.update', $supervise->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row-2">
                {{-- اختيار العميل --}}
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tag"></i> اسم العميل</label>
                    <input type="text" value="{{$supervise->customer->item->name}}" class="searcher" placeholder="ابحث عن العميل">
                    <select name="customer_id" id="clientSelect" size="5" class="client-search">
                        @foreach($selectedClient as $clnt)
                            <option value="{{ $clnt->id }}"
                                    @if($supervise->customer_id == $clnt->id) selected @endif>
                                {{ $clnt->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الموظف --}}
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tie"></i> الموظف</label>
                    <select name="employee_id">
                        <option disabled>اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                    @if($supervise->employee_id == $emp->id) selected @endif>
                                {{ $emp->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row-2">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> اسم الشخص</label>
                    <input type="text" name="name" value="{{ $supervise->name }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ $supervise->phone }}">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-exclamation-circle"></i> المشكلة</label>
                <textarea name="issue" rows="3">{{ $supervise->issue }}</textarea>
                <input type="hidden" name="issue_id" value="{{ $supervise->id }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-screwdriver-wrench"></i> طريقة الحل</label>
                <textarea name="solution_method" rows="3">{{ $supervise->solution_method }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_completed" value="1" class="delay-click checkbox"
                           @if($supervise->is_completed) checked @endif>
                    <i class="fas fa-check-circle"></i> تم الإنجاز
                </label>
            </div>

            <div class="form-group delay-rezone-label">
                <label><i class="fas fa-hourglass-half"></i> سبب التأجيل</label>
                <textarea name="delay_reason" class="delay-rezone-input">{{ $supervise->delay_reason }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="transferred_to_management" value="1" class="trans-click checkbox"
                           @if($supervise->transferred_to_management) checked @endif>
                    <i class="fas fa-building"></i> محولة للإدارة
                </label>
            </div>

            <div class="form-group trans-rezone-label">
                <label><i class="fas fa-comment-alt"></i> سبب التحويل</label>
                <textarea name="transfer_reason" class="trans-rezone-input">{{ $supervise->transfer_reason }}</textarea>
            </div>

            {{-- الصور --}}
            <div class="form-group image-inputs" id="image-inputs">
                <label>الصورة</label>
                <input type="file" multiple accept="image/*" capture="environment"
                       name="images[]" id="image">
                <button class="btn btn-primary" type="button" onclick="addImageInput()">+ إضافة صورة</button>

                {{-- عرض الصور الحالية --}}
                @if($supervise->images)
                    <div class="existing-images">
                        @foreach($supervise->images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="image" class="thumb">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- الموقع --}}
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> الموقع</label>
                <input type="text" name="location" class="location" value="{{ $supervise->location }}" readonly>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">تحديث</button>
                <a href="{{ route('supervises.index') }}" class="btn-cancel">إلغاء</a>
            </div>
        </form>

        {{-- الخريطة --}}
        <div class="map" style="display: none">
            <div id="map" class="map-container"></div>
        </div>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
    <script src="{{ asset('js/supervise.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</x-app-layout>
