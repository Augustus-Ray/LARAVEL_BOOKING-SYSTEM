<p class="text-muted mb-3">
    Ensure your account is using a long, random password to stay secure.
</p>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">Current Password</label>
        <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Password</button>

        @if (session('status') === 'password-updated')
            <span class="text-success small align-self-center">Saved!</span>
        @endif
    </div>
</form>
