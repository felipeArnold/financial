<h3 class="mb-2 px-1 font-semibold text-lg text-gray-400">
    {{ $status['title'] }}
    <div class="flex justify-between items-center mb-2">
        <span class="text-xs">
            {{ Number::currency(array_sum(array_column($status['records'], 'valuation')), 'BRL') }}
        </span>
        <span  class="text-xs">
            {{ Str::padLeft(count($status['records']), 2, 0) }} negócios
        </span>
    </div>
    <hr>
</h3>
