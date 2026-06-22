<section>
    <div class="sfp-card-title">Profile information</div>
    <p class="sfp-page-subtitle">Update your name and email address.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="sfp-stack mt-3">
        @csrf
        @method('patch')

        <div class="sfp-form-grid">
            <div>
                <label for="name" class="sfp-label">Name <span class="required">*</span></label>
                <input id="name" name="name" type="text" class="sfp-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')<div class="sfp-error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label for="email" class="sfp-label">Email <span class="required">*</span></label>
                <input id="email" name="email" type="email" class="sfp-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')<div class="sfp-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="sfp-alert sfp-alert-danger mb-0">
                <span>!</span>
                <div>
                    Your email address is unverified.
                    <button form="send-verification" class="sfp-inline-button" type="submit">Send verification email</button>
                </div>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="sfp-alert sfp-alert-success mb-0"><span>OK</span> A new verification link has been sent.</div>
            @endif
        @endif

        <div class="sfp-action-row">
            <button type="submit" class="sfp-btn sfp-btn-primary">Save profile</button>
            @if (session('status') === 'profile-updated')
                <span class="sfp-badge sfp-badge-success">Saved</span>
            @endif
        </div>
    </form>
</section>
