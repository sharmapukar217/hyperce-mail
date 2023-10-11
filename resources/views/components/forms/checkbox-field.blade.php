<x-forms.field-wrapper :name="$name" :label="$label">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['id' => 'id-field-' .  str_replace('[]', '', $name)]) }} {{ $checked ? 'checked' : '' }}>
</x-forms.field-wrapper>