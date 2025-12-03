<div class="filters-container">

    <style>
        .filters-container {
            padding: 15px;
            background: #ffffff;
        }

        .filters-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .filters-row label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }

        .filters-row input,
        .filters-row select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filter-buttons {
            margin-top: 10px;
        }

        .filter-buttons button {
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            margin-right: 10px;
        }

        .btn-search {
            background: #3490dc;
            color: white;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
        }
    </style>

    <form method="GET" action="">
        <div class="filters-row">

            {{-- المخزن --}}
            <div>
                <label>المخزن</label>
                <select name="store_id">
                    <option value="">الجميع</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- نوع الحركة --}}
            <div>
                <label>نوع الحركة</label>
                <select name="type">
                    <option value="">الجميع</option>
                    <option value="in" {{ request('type')=='in' ? 'selected' : '' }}>وارد</option>
                    <option value="out" {{ request('type')=='out' ? 'selected' : '' }}>صادر</option>
                </select>
            </div>

            {{-- المستخدم --}}
            <div>
                <label>المستخدم</label>
                <select name="user_id">
                    <option value="">الجميع</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id')==$u->id ? 'selected':'' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- الصنف --}}
            <div>
                <label>الصنف</label>
                <select name="item_id">
                    <option value="">الجميع</option>
                    @foreach($items as $i)
                        <option value="{{ $i->id }}" {{ request('item_id')==$i->id ? 'selected':'' }}>
                            {{ $i->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- من تاريخ --}}
            <div>
                <label>من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            {{-- إلى تاريخ --}}
            <div>
                <label>إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

        </div>

        <div class="filter-buttons">
            <button class="btn-search"><i class="fa fa-search"></i> بحث</button>
            <a href="" class="btn-reset"><i class="fa fa-undo"></i> تصفية</a>
        </div>

    </form>

</div>
