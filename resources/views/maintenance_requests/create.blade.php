<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة طلب صيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('maintenance_requests.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الأصل</label>
                    <!-- صندوق البحث -->
                    <input type="text" id="asset-search" placeholder="ابحث عن الأصل..." class="form-control">

                    <!-- القائمة المنسدلة -->
                    <select name="asset_id" id="asset-select" required>
                        <option value="">اختر الأصل</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>مقدم الطلب</label>
                    <select name="employee_id">
                        <option value="">لا يوجد</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group assign-section" id="reports-section">
                    <label>البلاغ</label>
                    <div class="search-filter">
                        <input type="text" id="report-name" placeholder="ابحث عن بلاغ " class="name-searcher">
                        <input id="report-date" type="date" class="name-searcher">

                        @foreach($reports as $item)
                            <div class="item-card"
                                 data-asset="{{ strtolower($item->item?->name ?? '') }}">
                                <label>
                                    <input type="radio"
                                           data-date="{{ $item->created_at }}"
                                           class="itemsCheck checkbox"
                                           name="report_id"
                                           value="{{ $item->id }}">
                                    <span>
                                        {{ ($item->controlUnit->name ?? $item->user_control_unit) . ' -> ' . ($item->item?->name ?? '') }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>المشكلة</label>
                    <textarea name="issue_text" required></textarea>
                    <label>نوع الطلب</label>
                    <select name="issue_type_id">
                        @foreach($issueTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <a href="{{ route('maintenance_requests.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>

    <script>
        // فلترة البلاغات بالاسم + التاريخ
        function initFilter(sectionId, textInputId, dateInputId) {
            const section = document.getElementById(sectionId);
            const textInput = document.getElementById(textInputId);
            const dateInput = document.getElementById(dateInputId);

            function filterItems() {
                const textValue = textInput ? textInput.value.toLowerCase() : "";
                const dateValue = dateInput ? dateInput.value : "";
                const items = section.querySelectorAll(".item-card");

                items.forEach(item => {
                    const itemText = item.innerText.toLowerCase();
                    const itemDate = item.querySelector("input[type=radio]").dataset.date || "";

                    let textMatch = textValue === "" || itemText.includes(textValue);
                    let dateMatch = dateValue === "" || itemDate.startsWith(dateValue);

                    item.style.display = (textMatch && dateMatch) ? "block" : "none";
                });
            }

            if (textInput) textInput.addEventListener("keyup", filterItems);
            if (dateInput) dateInput.addEventListener("change", filterItems);
        }

        initFilter("reports-section", "report-name", "report-date");

        // البحث عن الأصل
        const assetSearch = document.getElementById("asset-search");
        const assetSelect = document.getElementById("asset-select");

        assetSearch.addEventListener("keyup", function () {
            const searchValue = this.value.toLowerCase();
            const options = assetSelect.querySelectorAll("option");

            options.forEach(option => {
                if (option.value === "") return;
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(searchValue) ? "block" : "none";
            });

            const matchedOption = Array.from(options).find(
                option => option.textContent.toLowerCase() === searchValue
            );
            if (matchedOption) {
                assetSelect.value = matchedOption.value;
                filterReportsByAsset(matchedOption.textContent);
            }
        });

        // عند تغيير الأصل → فلترة البلاغات المرتبطة
        assetSelect.addEventListener("change", function () {
            const selectedText = this.options[this.selectedIndex].text.toLowerCase();
            filterReportsByAsset(selectedText);
        });

        function filterReportsByAsset(assetName) {
            const items = document.querySelectorAll("#reports-section .item-card");
            items.forEach(item => {
                const itemAsset = item.dataset.asset;
                item.style.display = assetName && !itemAsset.includes(assetName) ? "none" : "block";
            });
        }
    </script>
</x-app-layout>
