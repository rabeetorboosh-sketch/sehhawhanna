<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تقارير الحركات
        </h2>
    </x-slot>
    <style>
        .report-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            direction: rtl;

        }

        .section-title {
            font-size: 22px;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }

        .card-row {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .report-card {
            flex: 0 0 45%;
            background: #f8f8f8;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            padding: 25px 15px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 12px rgba(0,0,0,0.15);
        }

        .report-card .icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #fff;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .report-card .text {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 15px;
        }

        /* أزرار الخيارات داخل كل مربع */
        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .option-btn {
            background: #fff;
            color: #333;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid #ddd;
            transition: 0.3s;
            text-align: start;
        }

        .option-btn:hover {
            background: #f1f5f9;
        }

        .orange .icon { background: #f59e0b; }
        .green .icon { background: #10b981; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/report/cards.css') }}">

    <div class="report-container">
        <h3 class="section-title">اختر نوع التقرير</h3>

        <div class="card-row">

            {{-- 🟠 التقارير حسب العملية --}}
            <div class="report-card orange">
                <div class="icon"><i class="fas fa-random"></i></div>
                <div class="text">التقارير حسب العملية</div>

                <div class="options">
                    @foreach($movements as $movement)

                        <a href="{{ route('storeMovements.byOperationDetail', $movement->id) }}" class="option-btn">{{$movement->name}}</a>
                    @endforeach
                 </div>
            </div>

            <div class="report-card green">
                <div class="icon"><i class="fas fa-list"></i></div>
                <div class="text">القارير حسب </div>

                <div class="options">

                        <a href="{{ route('storeMovements.byStoreDetail')}}" class="option-btn">المستودع </a>
                        <a href="{{ route('storeMovements.byProductDetail')}}" class="option-btn">الصنف </a>
                        <a href="{{ route('storeMovements.byEmployeeDetail')}}" class="option-btn">الموظف </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
