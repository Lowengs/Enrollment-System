<link rel="stylesheet" href="../../CSS/faculty/managestudent.css?v=<?= time(); ?>" />
<?php 
include ('Layout/Layout.php');
include ('../../PHP/Queries-faculty.php');
?>
  <div class="container">
  <aside class="sidebar">
  <?php
      include ('Layout/Sidebar.php');
      ?>
    </aside>
    <main class="main-content">
      
      <header>
        <h1>Manage Students</h1>
        
        <form id="filterForm">
  <div>
    <label>Semester</label>
    <select name="semester" id="semester">
      <option value="" disabled selection>-- Select --</option>
      <option value="1">1st Semester</option>
      <option value="2">2nd Semester</option>
    </select>

    <label>Program</label>
    <select name="program" id="program">
      <option value="" disabled selection>-- Select --</option>
      <option value="BSAIT">Bachelor of Science in Aviation Information Technology</option>
      <option value="BSAIS">Bachelor of Science in Aviation Information System</option>
    </select>
<br>
    <label>Year Level</label>
    <select name="year" id="year">
      <option value="" disabled selection>-- Select --</option>
      <option value="1">1st Year</option>
      <option value="2">2nd Year</option>
      <option value="3">3rd Year</option>
      <option value="4">4th Year</option>
    </select>

    <button type="submit">Filter</button>
  </div>
</form>

<div id="studentList"></div>

      </header>
      
    </main>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $("#filterForm").on("submit", function(e) {
    e.preventDefault();

    var semester = $("#semester").val();
    var program = $("#program").val();
    var year = $("#year").val();

    $.ajax({
      url: "../../PHP/Student-fetch.php",
      method: "POST",
      data: { semester: semester, program: program, year: year },
      success: function(response) {
        $("#studentList").html(response);
      },
      error: function() {
        alert("Failed to fetch student data.");
      }
    });
  });
});
</script>
