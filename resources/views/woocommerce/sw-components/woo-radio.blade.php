<div class="sw-radios">
    @foreach($attrs as $attr)
        @if($attr['slug'] !== '')
            <div class="form-check form-check-inline">
                <input
                        type="radio"
                        name="{{ $tax_name }}"
                        class="form-check-input sw-radios__input"
                        value="{{ $attr['slug'] }}"
                        data-attribute_name="{{ $tax_name }}"
                        {{ $attr['default'] ? 'checked' : '' }}
                >
                <label class="form-check-label sw-radios__label">{{ $attr['name'] }}</label>
            </div>
        @endif

    @endforeach
</div>