<?php
session_start();
session_unset();
session_destroy();

header("Location: ../User/log-in.php");
exit;
