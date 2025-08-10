<p class="text-muted mb-3">
    Update your account's profile information and email address.
</p>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <div class="alert alert-warning small">
                    Your email address is unverified.
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                        Click here to re-send the verification email.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success small mt-2">
                        A new verification link has been sent to your email address.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        
        @if (session('status') === 'profile-updated')
            <span class="text-success small align-self-center">Saved!</span>
        @endif
    </div>
</form>
