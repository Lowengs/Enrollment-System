<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');


// Check if user is logged in
if (!isset($_SESSION['studentNo'])) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';
$studentNo = $_SESSION['studentNo'];

// Fetch password directly using studentNo
$stmt = $conn->prepare("SELECT password FROM student WHERE studentNo = ?");
$stmt->bind_param("i", $studentNo);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['current-password'])) {
        $error = "Please enter your current password.";
    } else {
        $enteredPassword = trim($_POST['current-password']);
        $passwordFromDB = $student['password'];

        if (password_verify($enteredPassword, $passwordFromDB)) {
            $_SESSION['password_verified'] = true;
            header('Location: editpass.php');
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    }
}

?>


<title>Verify Password</title>
<link rel="stylesheet" href="../../CSS/student/profile.css">

<style>
.container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 2rem;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.form-container {
    padding: 1rem;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input[type="password"]:focus {
    border-color: #4a90e2;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
}

.button-group {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 200px;
}

.btn-primary {
    background-color: #4a90e2;
    color: white;
}

.btn-primary:hover {
    background-color: #357abd;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
    text-align: center;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
</style>

<div class="container">
    <div class="form-container">
        <h2>Verify Your Password</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="password.php" method="POST">
            <div class="form-group">
                <label for="current-password">Current Password</label>
                <input type="password" 
                       id="current-password" 
                       name="current-password" 
                       placeholder="Enter your current password" 
                       required 
                       autocomplete="current-password">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Verify Password</button>
            </div>
        </form>
    </div>
</div>
