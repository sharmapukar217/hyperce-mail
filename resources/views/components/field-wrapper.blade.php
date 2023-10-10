<div {{ $attributes->merge(['class' => 'form-group row form-group-' . $name . ' ' . $wrapperClass  . ' '. $errorClass($name)]) }}>
    <x-label :name="$name">{{ $label }}</x-label>
    <div class="col-sm-9">
        {{ $slot }}
    </div>
</div>