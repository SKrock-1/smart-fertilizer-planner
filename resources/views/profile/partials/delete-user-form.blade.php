<section>
    <div class="sfp-card-title">Delete account</div>
    <p class="sfp-page-subtitle">This permanently deletes your account and all related farm data.</p>

    <form method="post" action="{{ route('profile.destroy') }}" class="sfp-stack mt-3">
        @csrf
        @method('delete')

        <div class="sfp-form-group">
            <label for="delete_password" class="sfp-label">Password <span class="required">*</span></label>
            <input id="delete_password" name="password" type="password" class="sfp-input @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Enter password to confirm">
            @foreach ($errors->userDeletion->get('password') as $message)
                <div class="sfp-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="sfp-action-row">
            <button type="submit" class="sfp-btn sfp-btn-danger sfp-delete-btn">Delete account</button>
        </div>
    </form>
</section>
