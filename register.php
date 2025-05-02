<?php 
include 'includes/header.php';

require_once 'includes/mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Generate verification token
    $verification_token = bin2hex(random_bytes(32));
    
    // Cek apakah username atau email sudah ada
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $user = mysqli_fetch_assoc($check_result);
        if ($user['username'] == $username) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $error = "Email sudah terdaftar. Silakan gunakan email lain.";
        }
    } else {
        // Insert user baru
        $query = "INSERT INTO users (nama, telepon, alamat, username, email, password, verification_token) 
                  VALUES ('$nama', '$telepon', '$alamat', '$username', '$email', '$password', '$verification_token')";
        
        if (mysqli_query($conn, $query)) {
            // Kirim email verifikasi
            $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/auu/verify.php?token=" . $verification_token;
            $email_body = "
                <h2>Verifikasi Email Anda</h2>
                <p>Terima kasih telah mendaftar di Futsal Sayan. Silakan klik link di bawah ini untuk memverifikasi email Anda:</p>
                <p><a href='{$verification_link}' style='background-color: #48bb78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verifikasi Email</a></p>
                <p>Atau copy paste link berikut ke browser Anda:</p>
                <p>{$verification_link}</p>
            ";
            
            if (kirimEmail($email, "Verifikasi Email - Futsal Sayan", $email_body)) {
                $success = "Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.";
            } else {
                $error = "Pendaftaran berhasil, tetapi gagal mengirim email verifikasi. Silakan hubungi admin.";
            }
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">Daftar Akun Baru</h2>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">
                Nama Lengkap
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="nama" 
                   type="text" 
                   name="nama" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="telepon">
                Nomor Telepon
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="telepon" 
                   type="tel" 
                   name="telepon" 
                   pattern="[0-9]+" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="alamat">
                Alamat
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      id="alamat" 
                      name="alamat" 
                      rows="3" 
                      required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="email" 
                   type="email" 
                   name="email" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Username
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="username" 
                   type="text" 
                   name="username" 
                   required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="password" 
                   type="password" 
                   name="password" 
                   required>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                    type="submit">
                Daftar
            </button>
        </div>
    </form>

    <p class="text-center mt-4 text-gray-600">
        Sudah punya akun? 
        <a href="login.php" class="text-green-600 hover:text-green-800">
            Masuk di sini
        </a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>
