<?php
include ('Connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FirstName  = $_POST['firstName'];
    $LastName   = $_POST['lastName'];
    $MiddleName = $_POST['middleName'];
    $Username   = $_POST['username'];
    $Password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO faculty (FirstName, LastName, MiddleName, Username, password)
              VALUES ('$FirstName', '$LastName', '$MiddleName', '$Username', '$Password')";

    if (mysqli_query($conn, $query)) {
         echo "<script>
        alert('Created a Faculty.');
        window.location.href = '../PAGES/registrar/Faculty.php';
    </script>";
       
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
