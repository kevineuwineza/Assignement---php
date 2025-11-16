<?php
$connect = new mysqli("localhost", "root", "", "classb");

if (isset($_POST['signup'])) {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    }
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $connect->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $success = "Account created successfully!";
            header("refresh:2;url=login.php");
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, #f093fb, #f5576c);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
        }

        .form-container {
            background: white;
            width: 100%;
            max-width: 470px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        }

        h2 {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #f5576c;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .message {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
        }

        .error {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 2px solid rgba(239, 68, 68, 0.25);
        }

        .success {
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
            border: 2px solid rgba(34, 197, 94, 0.25);
        }

        label {
            display: block;
            color: #374151;
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            margin-bottom: 22px;
            transition: all .3s;
        }

        input:focus {
            outline: none;
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.15);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(145deg, #f093fb, #f5576c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: .3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 147, 251, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #6b7280;
        }

        .login-link a {
            color: #f5576c;
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Create Account</h2>
        <p class="subtitle">Join our community by filling this form</p>

        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter your username" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="At least 6 characters" required>

            <button name="signup">Sign Up</button>
        </form>

        <p class="login-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </div>

</body>
</html>
