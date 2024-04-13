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
    {{ $record->name }}

    <span class="flex gap-1 align-items-center text-xs text-gray-400 dark:text-gray-500">
        {{ Number::currency($record->valuation, 'BRL') }}
    </span>

    <div class="flex gap-1 align-items-center text-xs text-gray-400 dark:text-gray-500 mt-2">
        <span class="flex gap-1">
            <x-heroicon-o-clock class="text-gray-400 h-4 w-4" />
            {{ $record->created_at->diffForHumans() }}
        </span>
    </div>
</div>
