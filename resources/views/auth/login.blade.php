<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Arsip Notaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; width: 100%; max-width: 400px; }
        .card-header { background: #fff; border-bottom: none; padding: 40px 40px 20px; text-align: center; }
        .card-body { padding: 0 40px 40px; }
        .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; background-color: #f8fafc; }
        .form-control:focus { border-color: #2563eb; background-color: #fff; box-shadow: none; }
        .btn-primary { border-radius: 10px; padding: 12px; font-weight: 700; background-color: #2563eb; border: none; width: 100%; margin-top: 20px; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .brand-logo { font-size: 1.5rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; margin-bottom: 5px; display: block; }
    </style>
</head>
<body>

    <div class="card card-login">
        <div class="card-header">
            <span class="brand-logo"><span class="text-primary">NOTARIS
            </span>MUHAMMAD IMAM SAFARI</span>
            <p class="text-muted small mb-0">Silakan login untuk mengakses sistem</p>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2 small rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@notaris.id" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary shadow-sm">MASUK SISTEM</button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted" style="font-size: 0.7rem;">&copy; 2026 Kantor Notaris Imam Safari</small>
            </div>
        </div>
    </div>

</body>
</html>