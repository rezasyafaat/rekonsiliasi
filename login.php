<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = trim($_POST['password']); // Mengambil password yang dimasukkan

    // Cek apakah username ada di database
    $stmt = $conn->prepare("SELECT id, username, password FROM mst_user_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password langsung (tanpa hashing)
        if ($password === $row['password']) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php"); // Redirect ke dashboard
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ada.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Nama" required>
            <input type="password" name="password" placeholder="Kata Sandi" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
