<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير مهام الموظف
        </h2>
    </x-slot>
    <style>
        .imp-name {
            width: 100%;
            background: linear-gradient(to bottom, var(--card), var(--bg));
            padding: 20px 5px;
        }
        .group-info{
            display: flex;
            flex-wrap: wrap;
        }
        .imp-percentage {
            width: 20%;
            height: 200px;
            background: #ffffff;
            border: 3px inset;
            margin: 10px;
            border-radius: 20px;
            padding: 5px;
            font-size: 20px;
            text-align: center;
        }
        .chart {
            width: 100%;
            height: 100%;
            margin: 0 auto;
        }
        #tasksChart, #ratingChart ,#overdueChart{
            height: 90% !important;
            margin: 0 auto;
        }
        @media (max-width: 640px){

            .imp-percentage{
                width: 90%;
            }
            .danger {
                background: radial-gradient(#4d0909d4, #080707d9) !important;
                color: white;
            }
        }
    </style>


        <form class="smart-form" action="{{ route('reportTasks.byEmployeeDetail',$id??'') }}" method="get" enctype="multipart/form-data">
<div class="row-5">
    <div class="form-group">
        <label>المسند</label>
        <select name="user_id">
            <option value=""> الكل</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}"  {{request('user_id')==$user->id?'selected':''}} >{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>من تاريخ </label>
        <input type="date" name="from_date" value="{{request('from_date')}}">
    </div>
    <div class="form-group">
        <label>الى تاريخ </label>
        <input type="date" name="to_date" value="{{request('to_date')}}">
    </div>
    <div class="actions" style="    margin: 20px 0 0 0;">
        <div class="form-group">
            <label style="color: transparent "> - </label>
            <button type="submit" class="btn btn-primary" style="font-size: 12px">فلترة</button>
        </div>

        <div class="form-group">
            <label style="color: transparent "> - </label>
            <a href="{{route('reportTasks.byEmployeeDetail',$id)}}" class= "btn btn-worn" style="font-size: 12px">  اعادة تعيين </a>
        </div>

    </div>
</div>



        </form>

    <div class="py-4">
    <div class="main-holder">
        @php
            $transToManagement = 0;
            $Overdue = 0;
$totalTasks=0;
$receivedTasks=0;
            foreach ($assignments as $assignment){

                if ($assignment->occurrences->isNotEmpty()){

                   $totalTasks += $assignment->occurrences->count();
                  $receivedTasks +=  $assignment->occurrences->filter(fn($a) =>  $a->receipt->isNotEmpty())->count();
                }else{
                    $totalTasks++;
                    $receivedTasks+=$assignment->receipt->isNotEmpty()?1:0;
                }
            }

             $remainingTasks = $totalTasks - $receivedTasks;

            // حساب التقييم العام
            $ratings = [];
            foreach ($assignments as $assignment) {
                foreach ($assignment->occurrences as $occ) {
                    if($occ->receipt?->first()?->completion_percentage !== null){
                        $ratings[] = $occ->receipt->first()->completion_percentage;
                    }
                }
                if($assignment->receipt?->first()?->completion_percentage !== null){
                    $ratings[] = $assignment->receipt->first()->completion_percentage;
                }
            }
            $averageRating = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
        @endphp
        <div class="imp-name">
            <span>اسم الموظف :</span>
            <strong>{{ $employee->item?->name }}</strong>
        </div>
        <div class="group-info">

            <div class="imp-percentage">
                <span>نسبة إنجاز المهام</span>
                <div class="chart">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
            <div class="imp-percentage">
                <span>التقييم العام</span>
                <div class="chart">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
            <div class="imp-percentage">
                <span>نسبة المهام المتأخرة</span>
                <div class="chart">
                    <canvas id="overdueChart"></canvas>
                </div>
            </div>

        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>المسنِد</th>
                        <th>التاريخ</th>
                        <th>التكرار</th>
                        <th>تم حلها</th>
                        <th>تم تحويلها للادارة</th>
                        <th>تم تجاوز وقتها</th>
                        <th>التقييم</th>
                        <th>--</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assignments as $assignment)
                        @php($overdue = $assignment->overdueDiff())
                        @if(!$assignment->occurrences->isEmpty())
                            @foreach($assignment->occurrences as $occurrences)
                                @php($OccurrenceOverdue = $occurrences?->overdueDiff())
                                <tr
                                    @if($OccurrenceOverdue && $OccurrenceOverdue['hours'] < 5 && $OccurrenceOverdue['hours'] >= 0 && $occurrences->receipt->isEmpty())
                                        class="worn"
                                    @elseif($OccurrenceOverdue && $OccurrenceOverdue['hours'] < 0 && $occurrences->receipt->isEmpty())
                                        class="danger"
                                    @elseif(!$occurrences?->receipt->isEmpty())
                                        class="done"
                                    @endif
                                >
                                    <td>{{ $assignment->id }}</td>
                                    <td>{{ ($assignment->task?->user_control_unit) ? $assignment->task?->user_control_unit."-" : $assignment->task?->controlUnit?->name."-" }} {{ $assignment->task?->item?->name }}</td>
                                    <td>{{ $assignment->user?->name }}</td>
                                    <td>{{ $occurrences->date }}</td>
                                    <td>
                                        @if($assignment->recurrence_type === 'daily')
                                            يومي
                                        @elseif($assignment->recurrence_type === 'weekly')
                                            أسبوعي
                                        @elseif($assignment->recurrence_type === 'monthly')
                                            شهري
                                        @else
                                            بدون تكرار
                                        @endif
                                    </td>
                                    <td>{{ $occurrences?->receipt->isEmpty() ? 'لا' : 'نعم' }}</td>
                                    <td>
                                        @if($assignment->receipt?->first()?->forwarded_to_management==1)
                                            نعم
                                            @php(++$transToManagement)
                                        @else
                                            لا
                                        @endif
                                    </td>
                                    <td>
                                        @if($occurrences->isOverdue())
                                            ب <strong>{{ $OccurrenceOverdue ? -$OccurrenceOverdue['hours'].':'.$OccurrenceOverdue['minutes'] : '' }}</strong> ساعات
                                            @php(++$Overdue)
                                        @else
                                            متبقي : {{ $OccurrenceOverdue ? $OccurrenceOverdue['hours'].':'.$OccurrenceOverdue['minutes'] : 'غير محدد' }} ساعات
                                        @endif
                                    </td>
                                    <td>{{ $occurrences->receipt?->first()?->completion_percentage ?? '--' }}%</td>
                                    <td>
                                        @if($occurrences->receipt && !$occurrences->receipt->isEmpty())
                                            <a href="{{ route('task_receipts.show',$occurrences->receipt?->first()->id) }}" class="btn btn-worn">عرض</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr
                                @if($overdue && $overdue['hours'] < 5 && $overdue['hours'] >= 0 && $assignment->receipt->isEmpty())
                                    class="worn"
                                @elseif($overdue && $overdue['hours'] < 0 && $assignment->receipt->isEmpty())
                                    class="danger"
                                @elseif(!$assignment->receipt->isEmpty())
                                    class="done"
                                @endif
                            >
                                <td>{{ $assignment->id }}</td>
                                <td>{{ ($assignment->task?->user_control_unit) ? $assignment->task?->user_control_unit."-" : $assignment->task?->controlUnit?->name."-" }} {{ $assignment->task?->item?->name }}</td>
                                <td>{{ $assignment->user?->name }}</td>
                                <td>{{ $assignment->created_at }}</td>
                                <td>
                                    @if($assignment->recurrence_type === 'daily')
                                        يومي
                                    @elseif($assignment->recurrence_type === 'weekly')
                                        أسبوعي
                                    @elseif($assignment->recurrence_type === 'monthly')
                                        شهري
                                    @else
                                        بدون تكرار
                                    @endif
                                </td>
                                <td>{{ $assignment->receipt->isEmpty() ? 'لا' : 'نعم' }}</td>
                                <td>
                                    @if($assignment->receipt?->first()?->forwarded_to_management==1)
                                        نعم
                                        @php(++$transToManagement)
                                    @else
                                        لا
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->isOverdue())
                                        ب <strong>{{ $overdue ? -$overdue['hours'].':'.$overdue['minutes'] : '' }}</strong> ساعات
                                        @php(++$Overdue)
                                    @else
                                        متبقي : {{ $overdue ? $overdue['hours'].':'.$overdue['minutes'] : 'غير محدد' }} ساعات
                                    @endif
                                </td>
                                <td>{{ $assignment->receipt?->first()?->completion_percentage ?? '--' }}%</td>
                                <td>
                                    @if(!$assignment->receipt->isEmpty())
                                        <a href="{{ route('task_receipts.show',$assignment->receipt?->first()->id) }}" class="btn btn-worn">عرض</a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie chart للمهام
        const ctx = document.getElementById('tasksChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['المهام المستلمة', 'المتبقية'],
                datasets: [{
                    data: [{{ $receivedTasks }}, {{ $remainingTasks }}],
                    backgroundColor: ['#4CAF50', '#FF5722'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = {{ $totalTasks }};
                                let value = context.raw;
                                let percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // شارت جديد للتقييم العام
        const ctx2 = document.getElementById('ratingChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['التقييم العام', 'المتبقي للوصول 100%'],
                datasets: [{
                    data: [{{ $averageRating }}, {{ 100 - $averageRating }}],
                    backgroundColor: ['#2196F3', '#E0E0E0'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
        const ctx3 = document.getElementById('overdueChart').getContext('2d');
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['المهام المتأخرة', 'المهام في الوقت'],
                datasets: [{
                    data: [{{ $Overdue }}, {{ $totalTasks - $Overdue }}],
                    backgroundColor: ['#f44336', '#8bc34a'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = {{ $totalTasks }};
                                let value = context.raw;
                                let percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="{{ asset('js/report/tableReport.js') }}"></script>
</x-app-layout>
