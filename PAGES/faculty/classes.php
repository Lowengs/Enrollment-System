
<?php 
include ('Layout/Layout.php');
include ('../../PHP/Queries-faculty.php');
?>
<body>
  <div class="container">
    <aside class="sidebar">
    <?php
      include ('Layout/Sidebar.php');
      ?>
    </aside>
    <main class="main-content">
      <header>
        <h1 style="margin: 20px;">Classes</h1>
      </header>
      <section class="subject-table" style="margin: 20px;">
 
      <div class="filter">
  <p>Filter</p>
  <form id="filterForm">
    <select name="semester" id="semester">
      <option value="">-- Semester --</option>
      <option value="1">1</option>
      <option value="2">2</option>
    </select>

    <select name="course" id="course">
      <option value="">-- Program --</option>
      <option value="BSAIT">Bachelor of Science in Aviation Information Technology</option>
      <option value="BSAIS">Bachelor of Science in Aviation Information System</option>
    </select>

    <button type="submit">Filter</button>
  </form>
</div>

<table border="1" cellpadding="10" cellspacing="0">
 <thead>
  <tr>
    <th>Subject ID</th>
    <th>Section</th>
    <th>Faculty</th>
    <th>Subject Code</th>
    <th>Subject Name</th>
    <th>Year Level</th>
    
    <th>Time & Date</th>
    <th>Credits</th>
    <th>Semester</th>
  </tr>
</thead>

  <tbody id="tableBody">
    <!-- Data will be loaded here -->
  </tbody>
</table>
</section>

      
    </main>
  </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // When form is submitted
  $("#filterForm").on("submit", function(e) {
    e.preventDefault();

    var semester = $("#semester").val();
    var course = $("#course").val();

    $.ajax({
      url: "../../PHP/fetch-class.php",
      method: "POST",
      data: { semester: semester, course: course },
      success: function(response) {
        $("#tableBody").html(response);
      },
      error: function() {
        alert("Failed to load classes.");
      }
    });
  });
});
</script>
