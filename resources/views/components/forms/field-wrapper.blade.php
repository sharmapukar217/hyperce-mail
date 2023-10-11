<div {{ $attributes->merge(['class' => 'form-group row form-group-' . $name . ' ' . $wrapperClass  . ' '. $errorClass($name)]) }}>
    <x-forms.label :name="$name">{{ $label }}</x-forms.label>
    <div class="col-sm-9">
        {{ $slot }}
    </div>
</div>