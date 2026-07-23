<title>Student Profile</title>
<?php
include('Layout/Layoutdashboard.php');
include('../../PHP/Queries-students.php');
?>

<div class="container">
  <div class="profile-header">
    <div class="profile-pic">
      <?php
      if ($picturePath && file_exists($picturePath)) {
          echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $picturePath) . '" style="width: 135px; height: 135px; border-radius: 50%; background-color: #ccc;">';
      } else {
          echo '<img src="default-profile.png" alt="Profile Picture" width="135" height="135" style="border-radius: 50%; background-color: #ccc;">';
      }
      ?>
    </div>
    <div class="profile-name">
      <h2><?php echo $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']; ?></h2>
      <p>Student ID: <?php echo $row['studentId']; ?></p>
    </div>
  </div>

  <div class="info-grid">
    <div class="info-item"><strong>First Name:</strong> <?php echo $row['firstName']; ?></div>
    <div class="info-item"><strong>Middle Name:</strong> <?php echo $row['middleName']; ?></div>
    <div class="info-item"><strong>Last Name:</strong> <?php echo $row['lastName']; ?></div>
    <div class="info-item"><strong>Email:</strong> <?php echo $row['Email']; ?></div>
    <div class="info-item"><strong>Program:</strong> <?php echo $row['Course']; ?></div>
    <div class="info-item"><strong>Status:</strong> <?php echo $row['status']; ?></div>
    <div class="info-item"><strong>Grade Level:</strong> <?php echo $row['grade_level']; ?></div>
    <div class="info-item"><strong>Date of Birth:</strong> <?php echo $row['DOB']; ?></div>
    <div class="info-item"><strong>Contact No.:</strong> <?php echo $row['contactNo']; ?></div>
    <div class="info-item"><strong>Address:</strong> <?php echo nl2br($row['Address']); ?></div>
    <div class="info-item"><strong>Nationality:</strong> <?php echo $row['Nationality']; ?></div>

    <div class="info-item">
      <strong>Good Moral Certificate:</strong><br>
      <?php
      if (!empty($row['goodMoral']) && file_exists($row['goodMoral'])) {
          echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['goodMoral']) . '" alt="Good Moral Certificate" style="max-width: 200px; height: auto; border: 1px solid #ccc;">';
      } else {
          echo 'Not Uploaded';
      }
      ?>
    </div>

    <div class="info-item">
      <strong>Grade Card:</strong><br>
      <?php
      if (!empty($row['gradeCard']) && file_exists($row['gradeCard'])) {
          echo '<img src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['gradeCard']) . '" alt="Grade Card" style="max-width: 200px; height: auto; border: 1px solid #ccc;">';
      } else {
          echo 'Not Uploaded';
      }
      ?>
    </div>
  </div>

  <div style="margin-top: 30px; text-align: center;">
    <a href="EditStudentProfile.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px; text-decoration: none; background-color: #007bff; color: white; border-radius: 5px;">Edit Profile</a>
  </div>
</div>
