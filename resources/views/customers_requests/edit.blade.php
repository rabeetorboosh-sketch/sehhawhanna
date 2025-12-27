<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل طلب عميل (#{{ $requestModel->id }})
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('css/requests.css')}}">

    <div class="forPC bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('customersRequests.update', $requestModel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="sections-bar">
                <div class="groups-container">
                    @foreach($groups as $group)
                        @php
                            $productsGroup = $products->filter(fn($p) => $p->item->sub_group_id == $group->id);
                        @endphp

                        <div class="mb-4">
                            <div class="group-header">
                                {{$group->name}} <i class="fas fa-layer-group"></i>
                            </div>
                            <div id="product-fields">
                                <div class="flex items-center">
                                    <div class="searchable-select">
                                        @foreach($productsGroup as $pro)
                                            @php
                                                $item = $requestModel->items->firstWhere('product_id', $pro->id);
                                                $count = old("items.$pro->id.count", $item?->count);
                                                $unit = old("items.$pro->id.unit", $item?->product_unit_id);
                                            @endphp
                                            <div class="S grp{{$pro->item->sub_group_id}} item-container" style="margin-bottom: 1px; display:flex;">
                                                <input value="{{ $pro->item->name }}" class="product-name" style="border:none" disabled>
                                                <input name="items[{{$pro->id}}][id]" value="{{ $pro->id }}" class="vlue-lbl">
                                                <select name="items[{{$pro->id}}][unit]" class="unit-select">
                                                    @foreach($pro->item->units as $u)
                                                        <option value="{{$u->id}}" {{ $unit == $u->id ? 'selected' : '' }}>{{$u->unit?->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="flex space-x-4">
                                                    <input type="number" name="items[{{$pro->id}}][count]" value="{{ $count }}" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count" placeholder="الكمية">
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

            <div>


                <div class="mb-4" style="display:inline-block;">
                    <label for="employee_id_pc">الموظف</label>
                    <select name="employee_id" id="employee_id_pc" class="emp-input employee-select" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $requestModel->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4" style="display:inline-block;">
                    <label for="sales_rout_id_pc">خط السير</label>
                    <select name="sales_rout_id" id="sales_rout_id_pc" class="emp-input" required>
                        <option value="">اختر خط السير</option>
                        @foreach($salesRouts as $route)
                            <option value="{{ $route->id }}" {{ old('sales_rout_id', $requestModel->sales_rout_id) == $route->id ? 'selected' : '' }}>
                                {{ $route->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $requestModel->description) }}</textarea>
            </div>

            <a href="{{ route('customersRequests.index') }}" class="btn btn-outline" style="margin-left: 10px;">
                <i class="fas fa-arrow-left"></i> إلغاء
            </a>
            <button type="submit" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-save"></i> تحديث
            </button>
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

        <form class="smart-form" action="{{ route('customersRequests.update', $requestModel->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="products" class="block text-sm font-medium text-gray-700 productlpl">اختر المنتجات</label>
                <div id="product-fields">
                    <div class="flex items-center mb-4">
                        <div class="searchable-select">
                            @foreach($products as $pro)
                                @php
                                    $item = $requestModel->items->firstWhere('product_id', $pro->id);
                                    $count = old("items.$pro->id.count", $item?->count);
                                    $unit = old("items.$pro->id.unit", $item?->product_unit_id);
                                @endphp
                                <div class="PH grp{{$pro->item?->sub_group_id}} item-container" style="margin-bottom: 1px;">
                                    <input value="{{ $pro->item?->name }}" class="product-name" style="border:none" disabled>
                                    <input name="items[{{ $pro->id }}][id]" value="{{ $pro->id }}" class="vlue-lbl">
                                    <select name="items[{{ $pro->id }}][unit]" class="unit-select">
                                        @foreach($pro->item->units as $u)
                                            <option value="{{$u->id}}" {{ $unit == $u->id ? 'selected' : '' }}>{{$u->unit?->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="flex space-x-4">
                                        <input type="number" name="items[{{$pro->id}}][count]" value="{{ $count }}" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 count" placeholder="الكمية">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="summaryPhone">الإجمالي: 0</div>
            </div>

            <div class="row-2">
                <div class="mb-4 form-group" style="display: inline-block;">
                    <label for="employee_id_phone">الموظف</label>
                    <select name="employee_id" id="employee_id_phone" class="emp-input employee-select" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $requestModel->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 form-group" style="display: inline-block;">
                    <label for="customer_id_phone">العميل</label>
                    <select name="customer_id" id="customer_id_phone" class="emp-input" required>
                        <option value="">اختر العميل</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $requestModel->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 form-group" style="display: inline-block;">
                    <label for="sales_rout_id_phone">خط السير</label>
                    <select name="sales_rout_id" id="sales_rout_id_phone" class="emp-input" required>
                        <option value="">اختر خط السير</option>
                        @foreach($salesRouts as $route)
                            <option value="{{ $route->id }}" {{ old('sales_rout_id', $requestModel->sales_rout_id) == $route->id ? 'selected' : '' }}>
                                {{ $route->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="description_phone" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea name="description" id="description_phone" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $requestModel->description) }}</textarea>
            </div>

            <div class="row-3">
                <a href="{{ route('customersRequests.index') }}" class="btn btn-worn" style="margin-left: 10px;">
                    <i class="fas fa-arrow-left"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> تحديث
                </button>
            </div>
        </form>
    </div>

    <script src="{{asset('js/requests.js')}}"></script>
</x-app-layout>
