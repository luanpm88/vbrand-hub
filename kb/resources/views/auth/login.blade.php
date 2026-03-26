<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AcelleMail KB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <style>
        body { background: #FEF7F2; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 420px; width: 100%; }
        .btn-primary { background: #E8571A; border-color: #E8571A; }
        .btn-primary:hover { background: #c94a15; border-color: #c94a15; }
        .form-control:focus { border-color: #E8571A; box-shadow: 0 0 0 .2rem rgba(232,87,26,.15); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/kb-icon.svg') }}" alt="" width="48" height="48" class="mb-2">
            </a>
            <h4 class="fw-bold text-dark">AcelleMail KB</h4>
            <p class="text-muted small">Sign in to manage the knowledge base</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success small">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger small">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-medium">Sign In</button>
                </form>
            </div>
        </div>

        <p class="text-center mt-3 small text-muted">
            <a href="{{ route('home') }}" class="text-decoration-none">&larr; Back to Knowledge Base</a>
        </p>
    </div>
</body>
</html>
