<?php
include('Layout/Layoutdashboard.php');

include('../../PHP/Queries-students.php');

if (!isset($_SESSION['password_verified']) || $_SESSION['password_verified'] !== true) {
    header('Location: password.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPass = $_POST['newPass'];
    $newPassC = $_POST['newPassC'];

    if ($newPass !== $newPassC) {
        echo "<p style='color: red; text-align: center;'>Passwords do not match. Please try again.</p>";
    } elseif (strlen($newPass) < 6) {
        echo "<p style='color: red; text-align: center;'>Password must be at least 6 characters long.</p>";
    } else {
        $studentNo = $_SESSION['studentNo'];

        // First get the studentId from studentNo
        $idStmt = $conn->prepare("SELECT studentId FROM student WHERE studentNo = ?");
        $idStmt->bind_param("i", $studentNo);
        $idStmt->execute();
        $idResult = $idStmt->get_result();
        $studentId = $idResult->fetch_assoc()['studentId'];

        if (!$studentId) {
            echo "<p style='color: red; text-align: center;'>Student record not found. Please contact support.</p>";
        } else {
            $stmt = $conn->prepare("UPDATE student SET password = ? WHERE studentId = ?");
            $stmt->bind_param("ss", $newPass, $studentId);

            if ($stmt->execute()) {
                // Clear the password verification session
                unset($_SESSION['password_verified']);
                echo "<p style='color: green; text-align: center;'>Password updated successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error updating password. Please try again later.</p>";
            }

            $stmt->close();
        }
    }
}

?>

<link rel="stylesheet" href="../../CSS/student/profile.css">

<style>
/*dito ko muna lagay tong design ng edit password*/

h2 {
  text-align: center;
  margin-bottom: 20px;
}

label {
  display: block;
  margin: 15px 0 5px;
}

input[type="password"] {
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 25px;
}

.btn {
  padding: 10px 15px;
  border: none;
  color: white;
  border-radius: 5px;
  cursor: pointer;
  flex: 1;
  margin: 0 5px;
}

.red { background-color: #d9534f; }
.green { background-color: #5cb85c; }
.blue { background-color: #0275d8; }

.btn:hover {
  opacity: 0.9;
}

/*dito ko muna lagay tong design ng edit password*/
</style>

<div class="container">
    <div class="form-container">
        <h2>Change Password</h2>
        <?php
      
        ?>
        <form method="POST">
            <label for="newPass">New Password</label>
            <input type="password" id="newPass" name="newPass" placeholder="Enter current password" required>

            <label for="newPassC">Confirm Password</label>
            <input type="password" id="newPassC" name="newPassC" placeholder="Enter current password" required>

   
            <div class="button-group">
            <button type="submit" class="btn green">Change Password</button>
            </div>
        </form>
    </div>
</div>


