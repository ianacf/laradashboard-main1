<form action="{{ $action }}" method="POST">
    @method($method ?? 'POST')
    @csrf
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="device_name" class="form-label">{{ __('Device Name') }}</label>
            <input type="text" name="device_name" id="device_name" required autofocus value="{{ old('device_name', $device->device_name ?? '') }}" placeholder="{{ __('Enter Device Name') }}" class="form-control">
        </div>
        <div class="mt-4">
            <label for="description" class="form-contorl">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="10">{!! old('description', $device->description ?? '') !!}</textarea>
        </div> 
        <div>
            <label for="api_key" class="form-label">{{ __('Device API Key') }}</label>
            <input type="text" name="api_key" id="api_key_name" required autofocus value="{{ old('api_key', $device->api_key ?? '') }}" placeholder="{{ __('Enter Device API Key') }}" class="form-control">
        </div>              
        <div>
            <x-inputs.combobox
                name="status"
                label="{{ __('Status') }}"
                placeholder="{{ __('Select Status') }}"
                :options="collect($statuses)->map(fn($name, $id) => ['value' => $id, 'label' => ucfirst($name)])->values()->toArray()"
                :selected="old('status', $device->status ?? '')"
                :searchable="false"
            />
        </div>
        <div>
            <x-inputs.combobox
                name="assigned_to"
                label="{{ __('Assigned To') }}"
                placeholder="{{ __('Select User') }}"
                :options="collect($users)->map(fn($name, $id) => ['value' => $id, 'label' => ucfirst($name)])->values()->toArray()"
                :selected="old('assigned_to', $device->assigned_to ?? '')"
                :searchable="true"
            />
        </div>
    </div>
    <div class="mt-6 flex justify-start gap-4">
        <button type="submit" class="btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.devices.index') }}" class="btn-default">{{ __('Cancel') }}</a>
    </div>
</form>