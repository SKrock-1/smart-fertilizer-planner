<button {{ $attributes->merge(['type' => 'submit', 'class' => 'sfp-btn sfp-btn-primary']) }}>
    {{ $slot }}
</button>
