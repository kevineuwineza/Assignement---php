<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$connect = new mysqli("localhost", "root", "", "classb");

// Default values
$firstname = $lastname = $gender = $province = "";

if (isset($_GET['phone_number'])) {
    $phone_number = $connect->real_escape_string($_GET['phone_number']);

    $stmt = $connect->prepare("SELECT firstname, lastname, gender, province FROM information WHERE phone_number=?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($firstname, $lastname, $gender, $province);
    $stmt->fetch();
}

if (isset($_POST['update'])) {
    $firstname = $connect->real_escape_string($_POST['fn']);
    $lastname  = $connect->real_escape_string($_POST['ln']);
    $gender    = $connect->real_escape_string($_POST['gender']);
    $province  = $connect->real_escape_string($_POST['province']);

    $update = $connect->prepare("UPDATE information SET firstname=?, lastname=?, gender=?, province=? WHERE phone_number=?");
    $update->bind_param("sssss", $firstname, $lastname, $gender, $province, $phone_number);

    if ($update->execute()) {
        $success = "Information updated successfully!";
    } else {
        $error = "Error updating record: " . $update->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update Record</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(145deg, #f093fb, #f5576c); min-height:100vh; padding:20px; }
    .main-header { background:white; max-width:1320px; margin:0 auto 25px; padding:22px 35px; border-radius:15px; box-shadow:0 12px 35px rgba(0,0,0,0.2); display:flex; justify-content:space-between; align-items:center; }
    .main-header h1 { color:#f5576c; font-size:26px; font-weight:800; }
    .account-area { display:flex; align-items:center; gap:18px; }
    .display-name { color:#2c3e50; font-weight:600; font-size:15px; }
    .logout-link { background: linear-gradient(145deg,#f093fb,#f5576c); color:white; padding:11px 24px; border-radius:10px; text-decoration:none; font-weight:700; transition: all 0.3s; }
    .logout-link:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(240,147,251,0.4); }
    .main-grid { display:flex; gap:25px; max-width:1320px; margin:0 auto; align-items:flex-start; }
    .create-section { background:white; flex:0 0 390px; padding:35px; border-radius:15px; box-shadow:0 12px 35px rgba(0,0,0,0.2); }
    .create-heading { font-size:24px; font-weight:800; color:#f5576c; margin-bottom:28px; text-align:center; padding-bottom:16px; border-bottom:3px solid #f5576c; }
    .status-alert { padding:12px 16px; border-radius:10px; margin-bottom:18px; text-align:center; font-size:14px; font-weight:600; }
    .status-error { background: rgba(239,68,68,0.12); color:#dc2626; border:2px solid rgba(239,68,68,0.25); }
    .status-success { background: rgba(34,197,94,0.12); color:#16a34a; border:2px solid rgba(34,197,94,0.25); }
    .field-group { margin-bottom:20px; }
    .field-group label { display:block; color:#374151; font-weight:700; margin-bottom:7px; font-size:14px; }
    .field-group input { width:100%; padding:13px 15px; border:2px solid #e5e7eb; border-radius:10px; font-size:15px; transition:all 0.3s; }
    .field-group input:focus { outline:none; border-color:#f5576c; box-shadow:0 0 0 3px rgba(245,87,108,0.1); }
    .create-btn { width:100%; padding:14px; background:linear-gradient(145deg,#f093fb,#f5576c); color:white; border:none; border-radius:10px; font-size:16px; font-weight:700; cursor:pointer; transition:all 0.3s; text-transform:uppercase; letter-spacing:1px; }
    .create-btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(240,147,251,0.4); }
</style>
</head>
<body>

<div class="main-header">
    <h1>Information Management System</h1>
    <div class="account-area">
        <span class="display-name">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</div>

<div class="main-grid">
    <div class="create-section">
        <h2 class="create-heading">Update Record</h2>
        
        <?php if (isset($error)): ?>
            <div class="status-alert status-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="status-alert status-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="field-group">
                <label>First Name</label>
                <input type="text" name="fn" value="<?php echo htmlspecialchars($firstname); ?>" required>
            </div>
            
            <div class="field-group">
                <label>Last Name</label>
                <input type="text" name="ln" value="<?php echo htmlspecialchars($lastname); ?>" required>
            </div>
            
            <div class="field-group">
                <label>Gender</label>
                <input type="text" name="gender" value="<?php echo htmlspecialchars($gender); ?>" required>
            </div>
            
            <div class="field-group">
                <label>Province</label>
                <input type="text" name="province" value="<?php echo htmlspecialchars($province); ?>" required>
            </div>
            
            <button type="submit" name="update" class="create-btn">Update Record</button>
        </form>
    </div>
</div>

</body>
</html>
