<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة تقرير جديد
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supervisors.css') }}">

    <div class="py-12">
        <form class="smart-form" method="POST" action="{{ route('supervises.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row-2">
                {{-- مسح الباركود --}}
                <div class="form-group">
                    <label><i class="fa-solid fa-barcode"></i> مسح باركود العميل</label>
                    <div class="barcode-input-container">
                        <input type="text" id="barcodeInput" placeholder="امسح باركود العميل هنا" class="barcode-input">
                        <button type="button" id="startCamera" class="camera-btn">
                            <i class="fa-solid fa-camera"></i>
                        </button>
                    </div>
                    <!-- مكان عرض كاميرا ومسح QR -->
                    <div id="qr-reader" style="display: none; width: 100%;"></div>

                    <div id="scannerStatus" class="scanner-status"></div>
                </div>

                {{-- اختيار العميل --}}
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tag"></i> اسم العميل</label>
                    <input type="text" class="searcher" placeholder="ابحث عن العميل">
                    <select name="customer_id" id="clientSelect" size="5" class="client-search">
                        @foreach($selectedClient as $clnt)
                            <option value="{{ $clnt->id }}"
                                    data-barcode="{{ $clnt->id ?? '' }}"
                                    data-phone="{{ $clnt->phone ?? '' }}"
                                    data-emp="{{ $clnt->employee_id ?? '' }}"
                                    @if(request('customer_id') == $clnt->id || (isset($issue) && $issue->client_id == $clnt->id)) selected @endif>
                                {{ $clnt->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- الموظف --}}
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tie"></i> الموظف</label>
                    <select name="employee_id" id="employee_id">
                        <option disabled selected>اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                    @if(request('employee_id') == $emp->id || (isset($issue) && $issue->employee_id == $emp->id)) selected @endif>
                                {{ $emp->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row-2">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> اسم الشخص</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="row-2">

            <div class="form-group">
                <label><i class="fas fa-exclamation-circle"></i> المشكلة</label>
                <textarea name="issue" rows="3">{{ isset($issue) ? $issue->issue_details : old('issue') }}</textarea>
                <input type="hidden" name="issue_id" value="{{ isset($issue) ? $issue->id : '' }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-screwdriver-wrench"></i> طريقة الحل</label>
                <textarea name="solution_method" rows="3">{{ old('solution_method') }}</textarea>
            </div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_completed" value="1" class="delay-click checkbox">
                    <i class="fas fa-check-circle"></i> تم الإنجاز
                </label>
            </div>

            <div class="form-group delay-rezone-label">
                <label><i class="fas fa-hourglass-half"></i> سبب التأجيل</label>
                <textarea name="delay_reason" class="delay-rezone-input">{{ old('delay_reason') }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="transferred_to_management" value="1" class="trans-click checkbox" >
                    <i class="fas fa-building"></i> محولة للإدارة
                </label>
            </div>

            <div class="form-group trans-rezone-label">
                <label><i class="fas fa-comment-alt"></i> سبب التحويل</label>
                <textarea name="transfer_reason" class="trans-rezone-input">{{ old('transfer_reason') }}</textarea>
            </div>

            {{-- الصور --}}
            <div class="form-group image-inputs" id="image-inputs">
                <label class="btn " for="image">     <i class="fa-solid fa-camera"></i></label>
                <input type="file" multiple accept="image/*" capture="environment"
                       name="images[]" id="image" style="display: none">
                <button class="btn btn-primary" type="button" onclick="addImageInput()" >+ إضافة صورة</button>
            </div>

            {{-- الموقع --}}
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> الموقع</label>
                <input type="text" name="location" class="location" readonly>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <a href="{{ route('supervises.index') }}" class="btn-cancel">إلغاء</a>
            </div>
        </form>

        {{-- الخريطة --}}
        <div class="map" style="display: none">
            <div id="map" class="map-container"></div>
        </div>
    </div>


    <script src="{{ asset('js/supervise.js') }}"></script>


    <!-- مكتبة مسح الباركود -->
    <script src="https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js"></script>


    <script src="{{asset('js/customer_session.js')}}"></script>

    <style>
        .barcode-input-container {
            display: flex;
            gap: 10px;
        }

        .barcode-input {
            flex: 1;
        }

        .camera-btn {
            background: #4a5568;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .camera-btn:hover {
            background: #2d3748;
        }

        .scanner-status {
            margin-top: 10px;
            min-height: 20px;
        }

        .scanner-status .success {
            color: #10b981;
            font-weight: bold;
        }

        .scanner-status .error {
            color: #ef4444;
            font-weight: bold;
        }

        .scanner-status .info {
            color: #3b82f6;
            font-weight: bold;
        }

        #barcodeScanner {
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</x-app-layout>
