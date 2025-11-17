<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | BLUD</title>
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
</head>

<body>
    <div class="card-login">
        <!-- Kiri -->
        <div class="card-left">
        </div>

        <!-- Kanan -->
        <div class="card-right">
            <h2>Selamat Datang di Inventory BLUD Pariwisata👋🏻</h2>
            <p>Silakan masuk menggunakan akun Anda untuk mengelola stok, permintaan, dan laporan.</p>

            <form action="" method="POST">
                @csrf
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
