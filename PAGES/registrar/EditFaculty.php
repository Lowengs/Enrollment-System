<?php
include ('../../PHP/Queries-registrar.php');

if (isset($_GET['facultyID'])) {
    $facultyID = (int)$_GET['facultyID'];

    $result = mysqli_query($conn, "SELECT * FROM faculty WHERE facultyID = $facultyID");
    $faculty = mysqli_fetch_assoc($result);

    if (!$faculty) {
        echo "Faculty not found.";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $MiddleName = $_POST['MiddleName'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    // Start building the query
    $query = "UPDATE faculty SET
                FirstName='$FirstName',
                LastName='$LastName',
                MiddleName='$MiddleName',
                Username='$Username'";

    // Only update password if it's not empty
    if (!empty($Password)) {
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
        $query .= ", password='$hashedPassword'";
    }

    $query .= " WHERE facultyID = $facultyID";

    if (mysqli_query($conn, $query)) {
        header("Location: Faculty.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include ('Layout/sidebar.php'); ?>
        <div class="col-md-9 col-lg-10 main-content">
            <h2>Edit Faculty</h2>
            <form method="POST">
                <label>First Name:</label>
                <input type="text" name="FirstName" value="<?php echo htmlspecialchars($faculty['FirstName']); ?>" required><br>
                
                <label>Last Name:</label>
                <input type="text" name="LastName" value="<?php echo htmlspecialchars($faculty['LastName']); ?>" required><br>
                
                <label>Middle Name:</label>
                <input type="text" name="MiddleName" value="<?php echo htmlspecialchars($faculty['MiddleName']); ?>"><br>
                
                <label>Username:</label>
                <input type="text" name="Username" value="<?php echo htmlspecialchars($faculty['Username']); ?>" required><br>
                
                <label>New Password (leave blank to keep current):</label>
                <input type="password" name="Password"><br>

                <button type="submit">Update Faculty</button>
            </form>
        </div>
    </div>
</div>
