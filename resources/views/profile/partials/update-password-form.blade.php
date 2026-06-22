<section>
    <div class="sfp-card-title">Update password</div>
    <p class="sfp-page-subtitle">Use a strong password to keep your farm records secure.</p>

    <form method="post" action="{{ route('password.update') }}" class="sfp-stack mt-3">
        @csrf
        @method('put')

        <div class="sfp-form-grid">
            <div>
                <label for="update_password_current_password" class="sfp-label">Current password</label>
                <input id="update_password_current_password" name="current_password" type="password" class="sfp-input @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
                @foreach ($errors->updatePassword->get('current_password') as $message)
                    <div class="sfp-error">{{ $message }}</div>
                @endforeach
            </div>

            <div>
                <label for="update_password_password" class="sfp-label">New password</label>
                <input id="update_password_password" name="password" type="password" class="sfp-input @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
                @foreach ($errors->updatePassword->get('password') as $message)
                    <div class="sfp-error">{{ $message }}</div>
                @endforeach
            </div>

            <div>
                <label for="update_password_password_confirmation" class="sfp-label">Confirm password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="sfp-input @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                    <div class="sfp-error">{{ $message }}</div>
                @endforeach
            </div>
        </div>

        <div class="sfp-action-row">
            <button type="submit" class="sfp-btn sfp-btn-primary">Save password</button>
            @if (session('status') === 'password-updated')
                <span class="sfp-badge sfp-badge-success">Saved</span>
            @endif
        </div>
    </form>
</section>
