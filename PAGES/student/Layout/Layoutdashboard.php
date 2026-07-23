
<link rel="stylesheet" href=" ../../CSS/header.css?v=<?= time(); ?>">
<link rel="stylesheet" href="../../CSS/student/dashboard.css?v=<?= time(); ?>">
<link rel="stylesheet" href="../../CSS/student/profile.css?v=<?= time(); ?>">

<link rel="icon" href="../../CSS/pictures/philsca-removebg-preview.png" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<header>
<img src="../../CSS/pictures/philsca-removebg-preview.png" alt="">
        <p >REPUBLIC OF THE PHILIPPINES <br>
           <span> PHILIPPINE STATE COLLEGE OF AERONAUTICS</span> <br>
            PICCIO GARDEN, VILLAMOR PASAY CITY </p>
            <img src="../../CSS/pictures/ics.png" alt="">
    </header>

<nav>
    <ul>
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="dashboard.php">Dashboard</a>
        </li>
        <li class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
            <a href="profile.php">Profile</a>
        </li>
        <li class="<?php echo ($current_page == 'classofferings.php') ? 'active' : ''; ?>">
            <a href="classofferings.php">Class Offerings</a>
        </li>
        <li class="<?php echo ($current_page == 'enrollment.php') ? 'active' : ''; ?>">
            <a href="enrollment.php">Enrollment</a>
        </li>
        <li class="<?php echo ($current_page == 'schedule.php') ? 'active' : ''; ?>">
            <a href="schedule.php">My Schedule</a>
        </li>
        <li class="<?php echo ($current_page == 'password.php') ? 'active' : ''; ?>">
            <a href="password.php">Password</a>
        </li>
        <li><a href="../student/layout/logout.php">Logout</a></li>
    </ul>
</nav>
