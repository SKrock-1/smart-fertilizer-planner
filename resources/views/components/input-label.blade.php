@props(['value'])

<label {{ $attributes->merge(['class' => 'sfp-label']) }}>
    {{ $value ?? $slot }}
</label>
