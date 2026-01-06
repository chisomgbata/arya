<div class="flex gap-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
    @foreach($totals as $label => $count)
        <div
            class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-200 dark:bg-gray-900 dark:border-gray-600">
            <span class="font-medium text-sm text-gray-700 dark:text-gray-200">{{ $label }}</span>
            <span
                class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-danger-600 rounded-full">
                {{ $count }}
            </span>
        </div>
    @endforeach
</div>

