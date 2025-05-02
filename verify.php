<?php
require_once 'config/database.php';

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Cek token verifikasi
    $query = "SELECT * FROM users WHERE verification_token = '$token' AND email_verified = 0";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Update status verifikasi
        $update = "UPDATE users SET email_verified = 1, verification_token = NULL WHERE verification_token = '$token'";
        if (mysqli_query($conn, $update)) {
            $success = "Email berhasil diverifikasi! Silakan login.";
        } else {
            $error = "Terjadi kesalahan saat verifikasi.";
        }
    } else {
        $error = "Token verifikasi tidak valid atau sudah digunakan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Futsal Sayan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Verifikasi Email</h2>
        
        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
            <div class="text-center mt-4">
                <a href="login.php" class="inline-block bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
                    Login Sekarang
                </a>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
            <div class="text-center mt-4">
                <a href="register.php" class="text-green-600 hover:text-green-800">
                    Kembali ke Halaman Pendaftaran
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
