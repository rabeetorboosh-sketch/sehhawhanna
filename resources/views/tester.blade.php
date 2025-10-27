<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $header  }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="add btn">
            <a href="{{$add_url}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table" id="orders">
                    <thead>
                    <tr>
                        @foreach($ths as $th)
                            <th>{{$th}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($trs as $tr)
                        <tr>
                            @foreach($tr as $th)
                                <td >{{$td ?? ''}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
