<link rel="stylesheet" href="../../CSS/faculty/addsubject.css?v=<?= time(); ?>" />
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
        <h1 style="margin: 10px 0;">Add Subjects</h1>
      </header>
      
      <div class="content">
        <form action="../../PHP/addSubject.php" method="POST">
          <div>
            <label for="course">Course</label>
            <select name="course" id="course" required>
              <option value="" disabled selected>Select Course</option>
              <option value="BSAIT">BS Aviation Information Technology</option>
              <option value="BSAIS">BS Aviation Information System</option>
            </select>
          </div>

          <div>
            <label for="subject-name">Subject Name</label>
            <input type="text" placeholder="Enter subject name" name="subject-name" id="subject-name" required />
          </div>
          
          <div>
            <label for="abbreviation">Subject Code</label>
            <input type="text" placeholder="Enter subject code" name="abbreviation" id="abbreviation" required />
          </div>

          <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
              <label for="credits">Credit Hours</label>
              <input type="number" placeholder="Hours" name="credits" id="credits" min="1" max="6" required />
            </div>
            <div style="flex: 1;">
              <label for="grade">Year Level</label>
              <select name="grade" id="grade" required>
                <option value="" disabled selected>Select Year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
              </select>
            </div>
            <div style="flex: 1;">
              <label for="semester">Semester</label>
              <select name="semester" id="semester" required>
                <option value="" disabled selected>Select Sem</option>
                <option value="1">1st Sem</option>
                <option value="2">2nd Sem</option>
              </select>
            </div>
          </div>

          <button type="submit">Add Subject</button>
        </form>
      </div>
    </main>
  </div>