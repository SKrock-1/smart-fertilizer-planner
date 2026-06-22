<button {{ $attributes->merge(['type' => 'submit', 'class' => 'sfp-btn sfp-btn-danger']) }}>
    {{ $slot }}
</button>
