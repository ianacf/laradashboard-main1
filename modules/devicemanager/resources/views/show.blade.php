<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    {!! Hook::applyFilters('tasks.after_breadcrumbs', '') !!}

    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $device->device_name }}
            </h3>
        </x-slot>

        <div class="prose max-w-none dark:prose-invert">
            {!! $device->description !!}

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Device API Key') }}: {{ $device->api_key}}
            </p>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Created at') }}: {{ $device->created_at->format('d M, Y h:i A') }}
            </p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Last updated at') }}: {{ $device->updated_at->format('d M, Y h:i A') }}
            </p>

            <p class="mt-1 text-sm">
                <span
                    class="badge">
                    {{ ucfirst($device->status) }}
                </span>
            </p>
        </div>
    </x-card>
</x-layouts.backend-layout>
