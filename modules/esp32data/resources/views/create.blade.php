<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    {!! Hook::applyFilters('esp32s.after_breadcrumbs', '') !!}

    <x-card>
        @include('esp32data::partials.form', [
            'action' => route('admin.esp32s.store'),
            'method' => 'POST',
            'esp32' => null,
        ])
    </x-card>
</x-layouts.backend-layout>