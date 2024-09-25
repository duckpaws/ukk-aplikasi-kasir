<?php
    session_start();
    include "config/koneksi.php";
    if(isset($_POST['username'])) {

        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $cek = mysqli_query($koneksi, "SELECT*FROM user WHERE username='$username' AND password='$password'");

        if(mysqli_num_rows($cek) > 0) {
            $data = mysqli_fetch_array($cek); 
            $_SESSION['user'] = $data;
            echo '<script>alert("Selamat datang! Jangan lupa logout setelah menggunakan aplikasi ini."); location.href="index.php"</script>'; 
        }else{
            echo '<script>alert("Username atau password salah!");</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashierly</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<style>
    /* Global Styles */
body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
}

/* Container */
.container {
    display: flex;
    width: 70%;
    max-width: 1000px;
    height: 500px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Left Side - Login Form */
.login-form {
    width: 50%;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-form h1 {
    font-size: 36px;
    margin: 0;
    color: #000;
}

.login-form p {
    color: #666;
    margin-bottom: 30px;
}

.login-form label {
    display: block; /* Ensures the label is on its own line */
    font-size: 14px;
    margin-bottom: 8px;
    color: #333;
}


.login-form input {
    width: 100%;
    max-width: 350px; /* Adjusted width */
    padding: 12px; /* Adjusted padding */
    margin-bottom: 20px;
    border: 1.5px solid #ddd; /* Adjusted border thickness */
    border-radius: 10px; /* Adjusted border radius */
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    font-weight: 400; /* Menggunakan Inter 600 untuk input */
    outline: none; /* Remove default outline */
    transition: border-color 0.3s ease; /* Smooth transition on focus */
}

.login-form input:focus {
    border-color: #0046D5; /* Border color on focus */
}

.login-form button {
    width: 25%;
    background-color: #0046D5 ;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 60px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
}

.login-form button:hover {
    background-color: #003bb3;
}

/* Right Side - Background Image */
.login-image {
    width: 50%;
    background: linear-gradient(to bottom, #00337C, #1C82AD, #03C988);
    position: relative;
    overflow: hidden; /* Mengatasi elemen yang overflow */
}

/* Pattern Decorative */
.login-image::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: repeating-linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.1),
        rgba(255, 255, 255, 0.1) 10px,
        transparent 10px,
        transparent 20px
    );
    opacity: 0.5; /* Adjust opacity to match the design */
}
</style>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Hello!</h1>
            <p>Please login to go to the app.</p>
            <form method="post">
                <label for="username"><b>Username</b></label>
                <input type="text" id="username" name="username" placeholder="Enter your username">
                
                <label for="password"><b>Password</b></label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
                
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="login-image">
            <!-- This area is for the background image -->
        </div>
    </div>
</body>
</html>
