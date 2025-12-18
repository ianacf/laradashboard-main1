<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    {!! Hook::applyFilters('esp32s.after_breadcrumbs', '') !!}

    <x-card>
        @include('esp32data::partials.form', [
            'action' => route('admin.esp32s.update', $esp32->id),
            'method' => 'PUT',
            'esp32' => $esp32
        ])
    </x-card>

    @push('scripts')
    <x-quill-editor :editor-id="'description'" />
    @endpush
</x-layouts.backend-layout>