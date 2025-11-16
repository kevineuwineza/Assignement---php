<?php
session_start();
$connect = new mysqli("localhost", "root", "", "classb");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $connect->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                if ($remember) {
                    setcookie("user_id", $user['id'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie("username", $user['username'], time() + (30 * 24 * 60 * 60), "/");
                }

                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "User not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

<style>
/* Same style theme as Dashboard (pink gradient, rounded cards) */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:linear-gradient(145deg, #f093fb, #f5576c);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.form-container{
    background:white;
    width:100%;
    max-width:420px;
    padding:40px 35px;
    border-radius:15px;
    box-shadow:0 12px 35px rgba(0,0,0,0.2);
}

h2{
    text-align:center;
    font-size:28px;
    font-weight:800;
    color:#f5576c;
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    color:#4b5563;
    font-size:14px;
    margin-bottom:25px;
}

.message{
    padding:12px 16px;
    border-radius:10px;
    font-size:14px;
    text-align:center;
    font-weight:600;
    margin-bottom:18px;
}

.error{
    background:rgba(239, 68, 68, 0.12);
    color:#dc2626;
    border:2px solid rgba(239, 68, 68, 0.25);
}

label{
    display:block;
    font-size:14px;
    color:#374151;
    font-weight:700;
    margin-bottom:6px;
}

input[type="email"],
input[type="password"]{
    width:100%;
    padding:13px 15px;
    border:2px solid #e5e7eb;
    border-radius:10px;
    font-size:15px;
    margin-bottom:20px;
    transition:all .3s;
}

input:focus{
    border-color:#f5576c;
    outline:none;
    box-shadow:0 0 0 3px rgba(245,87,108,0.1);
}

.remember-me{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:20px;
}

.remember-me input{
    width:18px;
    height:18px;
    cursor:pointer;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:linear-gradient(145deg,#f093fb,#f5576c);
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:all .3s;
    text-transform:uppercase;
    letter-spacing:1px;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(240,147,251,0.4);
}

.signup-link{
    text-align:center;
    margin-top:20px;
    color:#4b5563;
    font-size:14px;
}

.signup-link a{
    color:#f5576c;
    font-weight:700;
    text-decoration:none;
}

.signup-link a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="form-container">
    <h2>Welcome Back</h2>
    <p class="subtitle">Login to access your account</p>

    <?php if (isset($error)): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <div class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me for 30 days</label>
        </div>

        <button name="login">Login</button>
    </form>

    <p class="signup-link">
        Don't have an account? 
        <a href="signup.php">Sign up here</a>
    </p>
</div>

</body>
</html>
