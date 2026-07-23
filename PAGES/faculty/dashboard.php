<?php 
include ('../../PHP/Queries-faculty.php');
include ('Layout/Layout.php');

?>
  <div class="container">
  <aside class="sidebar">
      <?php
      include ('Layout/Sidebar.php');
      ?>
    </aside>
    <main class="main-content">
      
      <header>
        <h1>Welcome, <?php echo getFacultyName($conn); ?>!</h1>
      </header>
      <section class="cards">
        <div class="card">
          <h3>Total Classes</h3>
          <p><?php echo countFacultyClasses($conn); ?></p>
        </div>
        <div class="card">
          <h3>Subjects</h3>
          <p><?php echo countFacultySubjects($conn); ?></p>
        </div>
        <div class="card">
          <h3>Students</h3>
          <p><?php echo countFacultyStudents($conn); ?></p>
        </div>
      </section>

      <section class="classes-section">
        <h2>My Classes</h2>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Section</th>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Schedule</th>
                <th>Room</th>
                <th>Enrolled Students</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $classes = getFacultyClasses($conn);
              if (mysqli_num_rows($classes) > 0) {
                while ($class = mysqli_fetch_assoc($classes)) {
                  echo '<tr>';
                  echo '<td>' . htmlspecialchars($class['section_name']) . '</td>';
                  echo '<td>' . htmlspecialchars($class['Subjectcode']) . '</td>';
                  echo '<td>' . htmlspecialchars($class['subject_name']) . '</td>';
                  echo '<td>' . htmlspecialchars($class['day']) . ' ' . $class['start_time'] . ' - ' . $class['end_time'] . '</td>';
                  echo '<td>' . htmlspecialchars($class['room']) . '</td>';
                  echo '<td>' . $class['enrolled_students'] . '</td>';
                  echo '<td class="action-buttons">';
                  echo '<a href="EditClass.php?sectionid=' . urlencode($class['sectionid']) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a> ';
                  echo '<a href="../../PHP/DeleteClass.php?sectionid=' . urlencode($class['sectionid']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this class?\');"><i class="fas fa-trash"></i> Delete</a>';
                  echo '</td>';
                  echo '</tr>';
                }
              } else {
                echo '<tr><td colspan="7" class="text-center">No classes assigned yet.</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <style>
        .classes-section {
          margin-top: 2rem;
          background: white;
          padding: 1.5rem;
          border-radius: 8px;
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .classes-section h2 {
          color: #2c3e50;
          margin-bottom: 1.5rem;
          font-size: 1.5rem;
          font-weight: 600;
        }

        .table {
          width: 100%;
          border-collapse: collapse;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          border-radius: 8px;
          overflow: hidden;
        }

        .table thead th {
          background-color: #2c3e50;
          color: white;
          font-weight: 600;
          padding: 1rem;
          text-align: left;
          border-bottom: 2px solid #34495e;
        }

        .table tbody td {
          padding: 1rem;
          vertical-align: middle;
          border-bottom: 1px solid #eee;
          color: #333;
        }

        .table tbody tr:hover {
          background-color: #f8f9fa;
          transition: all 0.3s ease;
        }

        .action-buttons {
          display: flex;
          gap: 8px;
          justify-content: flex-start;
        }

        .btn {
          padding: 0.5rem 1rem;
          border-radius: 4px;
          font-size: 0.875rem;
          font-weight: 500;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          transition: all 0.3s ease;
          border: none;
          cursor: pointer;
        }

        .btn-primary {
          background-color: #3498db;
          color: white;
        }

        .btn-primary:hover {
          background-color: #2980b9;
          transform: translateY(-1px);
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-danger {
          background-color: #e74c3c;
          color: white;
        }

        .btn-danger:hover {
          background-color: #c0392b;
          transform: translateY(-1px);
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn i {
          font-size: 0.875rem;
        }

        .text-center {
          text-align: center;
        }

        .table-responsive {
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
          border-radius: 8px;
        }

        @media (max-width: 768px) {
          .classes-section {
            padding: 1rem;
          }

          .table thead th,
          .table tbody td {
            padding: 0.75rem;
          }

          .action-buttons {
            flex-direction: column;
            gap: 4px;
          }

          .btn {
            width: 100%;
            justify-content: center;
          }
        }
      </style>
    </main>
  </div>
</body>
</php>
