<?php
session_start();
session_unset();
session_destroy();

header("location: ../php/index.php?error=logout");
exit();
