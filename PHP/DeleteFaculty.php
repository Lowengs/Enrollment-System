<?php
include ('Connection.php');


if (isset($_GET['facultyID'])) {
    $facultyID = $_GET['facultyID'];

    $query = "DELETE FROM faculty WHERE facultyID = $facultyID";

    if (mysqli_query($conn, $query)) {
        header("Location: ../PAGES/registrar/faculty.php");
        exit();
    } else {
        echo "Error deleting faculty: " . mysqli_error($conn);
    }
} else {
    echo "No faculty selected.";
}

?>
