<title>Edit Student Profile</title>
<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');
?>
<link rel="stylesheet" href="../../CSS/student/editprofile.css">
<div class="container" style="margin-top: 30px; max-width: 800px;">
  <div class="card shadow p-4">
    <h2 class="text-center mb-4">Edit Student Profile</h2>

    <form action="../../PHP/UpdatestudPfp.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="studentId" value="<?php echo $row['studentId']; ?>">

      <div class="form-group mb-3">
        <label><strong>First Name:</strong></label>
        <input type="text" name="firstName" class="form-control" value="<?php echo $row['firstName']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Middle Name:</strong></label>
        <input type="text" name="middleName" class="form-control" value="<?php echo $row['middleName']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Last Name:</strong></label>
        <input type="text" name="lastName" class="form-control" value="<?php echo $row['lastName']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Email:</strong></label>
        <input type="email" name="Email" class="form-control" value="<?php echo $row['Email']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Date of Birth:</strong></label>
        <input type="date" name="DOB" class="form-control" value="<?php echo $row['DOB']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Contact No.:</strong></label>
        <input type="text" name="contactNo" class="form-control" value="<?php echo $row['contactNo']; ?>">
      </div>

      <div class="form-group mb-3">
        <label><strong>Address:</strong></label>
        <textarea name="Address" class="form-control" rows="3"><?php echo $row['Address']; ?></textarea>
      </div>

      <div class="form-group mb-3">
        <label><strong>Nationality:</strong></label>
        <input type="text" name="Nationality" class="form-control" value="<?php echo $row['Nationality']; ?>">
      </div>

      <hr class="my-4">

      <h5>Uploads</h5>

      <div class="form-group mb-3">
  <label><strong>Profile Picture:</strong></label><br>
  <?php
  if (!empty($row['picture']) && file_exists($row['picture'])) {
      echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['picture']) . '" width="150" class="mb-2"><br>';
  } else {
      echo '<img src="default-profile.png" alt="Profile Picture" width="150" class="mb-2"><br>';
  }
  ?>
  <input type="file" name="picture" class="form-control-file">
</div>

<div class="form-group mb-3">
  <label><strong>Good Moral Certificate:</strong></label><br>
  <?php
  if (!empty($row['goodMoral']) && file_exists($row['goodMoral'])) {
      echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['goodMoral']) . '" width="150" class="mb-2"><br>';
  }
  ?>
  <input type="file" name="goodMoral" class="form-control-file">
</div>

<div class="form-group mb-4">
  <label><strong>Grade Card:</strong></label><br>
  <?php
  if (!empty($row['gradeCard']) && file_exists($row['gradeCard'])) {
      echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['gradeCard']) . '" width="150" class="mb-2"><br>';
  }
  ?>
  <input type="file" name="gradeCard" class="form-control-file">
</div>


      <div class="text-center">
        <button type="submit" class="btn btn-success px-4">Save Changes</button>
        <a href="profile.php?studentId=<?php echo $row['studentNo']; ?>" class="btn btn-secondary px-4 ms-2">Cancel</a>
      </div>
    </form>
  </div>
</div>
