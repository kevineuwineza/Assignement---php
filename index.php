<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['logged_in']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['logged_in'] = true;
}

$connect = new mysqli("localhost", "root", "", "classb");

if (isset($_POST["submit"])) {
    $phone_number = $_POST['number'];
    $firstname = $_POST['fn'];
    $lastname = $_POST['ln'];
    $gender = $_POST['gender'];
    $province = $_POST['province'];
    
    $phone_number = mysqli_real_escape_string($connect, $phone_number);
    $firstname = mysqli_real_escape_string($connect, $firstname);
    $lastname = mysqli_real_escape_string($connect, $lastname);
    $gender = mysqli_real_escape_string($connect, $gender);
    $province = mysqli_real_escape_string($connect, $province);
    
    $stmt = $connect->prepare("INSERT INTO information (phone_number, firstname, lastname, gender, province) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $phone_number, $firstname, $lastname, $gender, $province);
    
    if ($stmt->execute()) {
        $success = "Record added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
            padding: 20px;
        }
        
        .main-header {
            background: white;
            max-width: 1320px;
            margin: 0 auto 25px;
            padding: 22px 35px;
            border-radius: 15px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .main-header h1 {
            color: #f5576c;
            font-size: 26px;
            font-weight: 800;
        }
        
        .account-area {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        
        .display-name {
            color: #2c3e50;
            font-weight: 600;
            font-size: 15px;
        }
        
        .logout-link {
            background: linear-gradient(145deg, #f093fb, #f5576c);
            color: white;
            padding: 11px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }
        
        .logout-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 147, 251, 0.4);
        }
        
        .main-grid {
            display: flex;
            gap: 25px;
            max-width: 1320px;
            margin: 0 auto;
            align-items: flex-start;
        }
        
        .create-section {
            background: white;
            flex: 0 0 390px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        
        .create-heading {
            font-size: 24px;
            font-weight: 800;
            color: #f5576c;
            margin-bottom: 28px;
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 3px solid #f5576c;
        }
        
        .status-alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-error {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 2px solid rgba(239, 68, 68, 0.25);
        }
        
        .status-success {
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
            border: 2px solid rgba(34, 197, 94, 0.25);
        }
        
        .field-group {
            margin-bottom: 20px;
        }
        
        .field-group label {
            display: block;
            color: #374151;
            font-weight: 700;
            margin-bottom: 7px;
            font-size: 14px;
        }
        
        .field-group input {
            width: 100%;
            padding: 13px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .field-group input:focus {
            outline: none;
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }
        
        .create-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(145deg, #f093fb, #f5576c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 147, 251, 0.4);
        }
        
        .display-section {
            flex: 1;
            background: white;
            border-radius: 15px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .display-header {
            padding: 24px 32px;
            background: linear-gradient(145deg, #f093fb, #f5576c);
        }
        
        .display-header h2 {
            color: white;
            font-size: 22px;
            font-weight: 800;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 16px 18px;
            text-align: left;
            font-weight: 800;
            color: #f5576c;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1.5px;
        }
        
        td {
            padding: 16px 18px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-size: 14px;
        }
        
        tbody tr {
            transition: background 0.2s;
        }
        
        tbody tr:nth-child(even) {
            background: #fafafa;
        }
        
        tbody tr:hover {
            background: #fef2f8;
        }
        
        .btn-link {
            color: #f5576c;
            text-decoration: none;
            font-weight: 700;
            padding: 6px 13px;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-block;
            font-size: 13px;
        }
        
        .btn-link:hover {
            background: #f5576c;
            color: white;
            transform: scale(1.05);
        }
        
        @media (max-width: 1024px) {
            .main-grid {
                flex-direction: column;
            }
            
            .create-section {
                flex: 1;
                width: 100%;
                max-width: 520px;
                margin: 0 auto 25px;
            }
            
            .main-header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
        
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 11px 8px;
            }
        }
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
            <h2 class="create-heading">Add New Record</h2>
            
            <?php if (isset($error)): ?>
                <div class="status-alert status-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="status-alert status-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="" method="post">
                <div class="field-group">
                    <label>Phone Number</label>
                    <input type="number" name="number" placeholder="Enter phone number" required>
                </div>
                
                <div class="field-group">
                    <label>First Name</label>
                    <input type="text" name="fn" placeholder="Enter first name" required>
                </div>
                
                <div class="field-group">
                    <label>Last Name</label>
                    <input type="text" name="ln" placeholder="Enter last name" required>
                </div>
                
                <div class="field-group">
                    <label>Gender</label>
                    <input type="text" name="gender" placeholder="Enter gender" required>
                </div>
                
                <div class="field-group">
                    <label>Province</label>
                    <input type="text" name="province" placeholder="Enter province" required>
                </div>
                
                <button type="submit" name="submit" class="create-btn">Add Record</button>
            </form>
        </div>
        
        <div class="display-section">
            <div class="display-header">
                <h2>All Records</h2>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Phone Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Province</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sele = $connect->query("SELECT * FROM information");
                        while($row = mysqli_fetch_array($sele)){
                            $phone_number = $row['phone_number'];
                            $firstname = $row['firstname'];
                            $lastname = $row['lastname'];
                            $gender = $row['gender'];
                            $province = $row['province'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($phone_number); ?></td>
                            <td><?php echo htmlspecialchars($firstname); ?></td>
                            <td><?php echo htmlspecialchars($lastname); ?></td>
                            <td><?php echo htmlspecialchars($gender); ?></td>
                            <td><?php echo htmlspecialchars($province); ?></td>
                            <td><a href="update.php?phone_number=<?php echo urlencode($phone_number); ?>" class="btn-link">Update</a></td>
                            <td><a href="delete.php?phone_number=<?php echo urlencode($phone_number); ?>" class="btn-link">Delete</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>