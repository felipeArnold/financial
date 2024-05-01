<div
    id="{{ $record->getKey() }}"
    wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="record bg-white dark:bg-gray-700 rounded-lg px-4 py-2 cursor-grab font-medium text-gray-600 dark:text-gray-200"
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3)
        x-data
        x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        "
    @endif
>
    <div class="flex gap-1 align-items-center justify-between">
        {{ $record->name }}

        <span class="flex gap-1 align-items-center text-xs text-gray-400 dark:text-gray-500 mt-3">
            {{ Number::currency($record->valuation, 'BRL') }}
        </span>
    </div>

    <span class="flex gap-1 align-items-center flex-col text-sm text-gray-400 dark:text-gray-500">
        {{ $record->lead->name }}

        @if ($record->lead->email)
            <span class="flex gap-1">
                <x-heroicon-o-envelope class="h-4 w-4 text-gray-400" />
            {{ $record->lead->email }}
            </span>
        @endif

        @if ($record->lead->phone)
            <span class="flex gap-1">
                <x-heroicon-o-phone class="h-4 w-4 text-gray-400" />
            {{ $record->lead->phone }}
            </span>
        @endif
    </span>



    <div class="flex gap-1 align-items-center justify-between text-xs text-gray-400 dark:text-gray-500 mt-2">
        <span class="flex gap-1 text-xs">
            <x-heroicon-o-clock class="text-gray-400 h-4 w-4" />
            {{ $record->created_at->diffForHumans() }}
        </span>

        @if ($record->responsible->avatar)
            <img
                class="w-5 h-5 rounded-full"
                src="{{ Storage::url($record->responsible->avatar) }}"
                alt="{{ $record->responsible->name }}"
            >
        @else
            <div class="relative inline-flex items-center justify-center w-5 h-5 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
            <span class="font-medium text-gray-600 dark:text-gray-300 p-2">
                {{ \App\Helpers\FormatterHelper::abbreviationName($record->responsible->name) }}
            </span>
            </div>
        @endif

    </div>
</div>
