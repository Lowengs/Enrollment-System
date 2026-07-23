<?php
include('../../PHP/Queries-registrar.php');
?>
<title>Registrar Dashboard</title>
<style>
    .schedule-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .schedule-item:last-child {
        border-bottom: none;
    }
    .room-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .room-item:last-child {
        border-bottom: none;
    }
    .faculty-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .faculty-item:last-child {
        border-bottom: none;
    }
    .table td {
        vertical-align: top;
    }
    .table-container {
        overflow-x: auto;
    }
    .table {
        min-width: 1000px;
    }
</style>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include ('Layout/sidebar.php'); ?>

        <div class="col-md-9 col-lg-10 main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <h2>Sections Overview</h2>
                </div>
                
                <div class="table-container">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 15%">Section Name</th>
                                <th style="width: 15%">Rooms</th>
                                <th style="width: 20%">Faculty</th>
                                <th style="width: 30%">Schedules</th>
                                <th style="width: 10%">Total Enrolled Students</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = getAllSectionsWithEnrolledCount($conn);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Format rooms
                                    $rooms = explode(',', $row['rooms']);
                                    $roomsHtml = '';
                                    foreach ($rooms as $room) {
                                        $roomsHtml .= "<div class='room-item'>" . trim($room) . "</div>";
                                    }

                                    // Format faculty
                                    $faculty = explode(',', $row['FacultyNames']);
                                    $facultyHtml = '';
                                    foreach ($faculty as $f) {
                                        $facultyHtml .= "<div class='faculty-item'>" . trim($f) . "</div>";
                                    }

                                    // Format schedules
                                    $schedules = explode(',', $row['schedules']);
                                    $schedulesHtml = '';
                                    foreach ($schedules as $schedule) {
                                        $schedulesHtml .= "<div class='schedule-item'>" . trim($schedule) . "</div>";
                                    }

                                    echo "<tr>
                                            <td><strong>{$row['section_name']}</strong></td>
                                            <td>{$roomsHtml}</td>
                                            <td>{$facultyHtml}</td>
                                            <td>{$schedulesHtml}</td>
                                            <td class='text-center'><strong>{$row['enrolled_students']}</strong></td>
                                            <td class='text-center'>
                                                <div class='action-buttons'>
                                                    <a href='../../PHP/DeleteSection.php?section_name=" . urlencode($row['section_name']) . "' 
                                                       class='btn btn-danger btn-sm' 
                                                       onclick='return confirm(\"Are you sure you want to delete this section? This will affect all schedules under this section.\");'>
                                                        <i class='fas fa-trash'></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No sections found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
