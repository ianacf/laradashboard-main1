<form action="{{ $action }}" method="POST">
    @method($method ?? 'POST')
    @csrf
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="sensor" class="form-label">{{ __('Esp32 Sensor') }}</label>
            <input type="text" name="sensor" id="sensor" required autofocus value="{{ old('sensor', $esp32->sensor ?? '') }}" placeholder="{{ __('Enter Esp32 Sensor') }}" class="form-control">
        </div>
        <div>
            <label for="location" class="form-label">{{ __('Esp32 Location') }}</label>
            <input type="text" name="location" id="location" required autofocus value="{{ old('location', $esp32->location ?? '') }}" placeholder="{{ __('Enter Esp32 Location') }}" class="form-control">
        </div>
        <div>
            <label for="value1" class="form-label">{{ __('Esp32 Value1') }}</label>
            <input type="text" name="value1" id="value1" required autofocus value="{{ old('value1', $esp32->value1 ?? '') }}" placeholder="{{ __('Enter Esp32 Value1') }}" class="form-control">
        </div>
        <div>
            <label for="value1" class="form-label">{{ __('Esp32 Value2') }}</label>
            <input type="text" name="value2" id="value2" required autofocus value="{{ old('value2', $esp32->value2 ?? '') }}" placeholder="{{ __('Enter Esp32 Value2') }}" class="form-control">
        </div>
        <div>
            <label for="value1" class="form-label">{{ __('Esp32 Value3') }}</label>
            <input type="text" name="value3" id="value3" required autofocus value="{{ old('value3', $esp32->value3 ?? '') }}" placeholder="{{ __('Enter Esp32 Value3') }}" class="form-control">
        </div>
    </div>
    <div class="mt-6 flex justify-start gap-4">
        <button type="submit" class="btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.esp32s.index') }}" class="btn-default">{{ __('Cancel') }}</a>
    </div>
</form>