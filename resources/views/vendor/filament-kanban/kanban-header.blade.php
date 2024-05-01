<h3 class="mb-2 px-4 font-semibold text-lg text-gray-400">
    {{ $status['title'] }}
    <div class="flex justify-between items-center">
        <span class="text-xs">
            {{ Number::currency(array_sum(array_column($status['records'], 'valuation')), 'BRL') }}
        </span>
        <span  class="text-xs">
            {{ count($status['records']) }} negócios
        </span>
    </div>
</h3>
