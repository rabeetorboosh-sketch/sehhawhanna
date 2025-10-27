<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل حركة الأصل</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('asset_movements.update', $movement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <x-input-label for="asset_number" :value="'رقم الأصل'" />
                        <x-text-input id="asset_number" name="asset_number" type="number" value="{{ $movement->asset_number }}" required class="mt-1 block w-full"/>

                        <x-input-label for="from_item" :value="'من أيتم'" />
                        <x-text-input id="from_item" name="from_item" type="number" value="{{ $movement->from_item }}" required class="mt-1 block w-full"/>

                        <x-input-label for="from_item_type" :value="'نوع من أيتم'" />
                        <x-text-input id="from_item_type" name="from_item_type" type="number" value="{{ $movement->from_item_type }}" required class="mt-1 block w-full"/>

                        <x-input-label for="to_item" :value="'إلى أيتم'" />
                        <x-text-input id="to_item" name="to_item" type="number" value="{{ $movement->to_item }}" required class="mt-1 block w-full"/>

                        <x-input-label for="to_item_type" :value="'نوع إلى أيتم'" />
                        <x-text-input id="to_item_type" name="to_item_type" type="number" value="{{ $movement->to_item_type }}" required class="mt-1 block w-full"/>

                        <x-input-label for="movement_datetime" :value="'تاريخ ووقت النقل'" />
                        <x-text-input id="movement_datetime" name="movement_datetime" type="datetime-local" value="{{ \Carbon\Carbon::parse($movement->movement_datetime)->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full"/>

                        <x-input-label for="reason" :value="'السبب'" />
                        <textarea id="reason" name="reason" class="mt-1 block w-full border rounded p-2">{{ $movement->reason }}</textarea>

                        <x-input-label for="asset_status" :value="'حالة الأصل'" />
                        <x-text-input id="asset_status" name="asset_status" type="number" value="{{ $movement->asset_status }}" required class="mt-1 block w-full"/>

                        <x-input-label for="movement_destination" :value="'وجهة النقل'" />
                        <x-text-input id="movement_destination" name="movement_destination" type="number" value="{{ $movement->movement_destination }}" class="mt-1 block w-full"/>

                        <div class="flex justify-end mt-4">
                            <x-primary-button>تحديث</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
