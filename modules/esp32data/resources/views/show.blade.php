<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    {!! Hook::applyFilters('esp32s.after_breadcrumbs', '') !!}

    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $esp32->sensor }}
            </h3>
        </x-slot>

        <div class="prose max-w-none dark:prose-invert">
            {!! $esp32->location !!}

            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Created at') }}: {{ $esp32->created_at->format('d M, Y h:i A') }}
            </p>
            <p class="mt-1 text-sm">
                <span
                    class="badge">
                    {{ ucfirst($esp32->value1) }}
                </span>
            </p>

            <p class="mt-1 text-sm">
                <span
                    class="badge">
                    {{ ucfirst($esp32->value2) }}
                </span>
            </p>
        </div>
    </x-card>
</x-layouts.backend-layout>
