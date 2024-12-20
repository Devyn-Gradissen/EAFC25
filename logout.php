<?php
session_start();
session_unset();
session_destroy();
header("Location: http://localhost/EAFC25/index.php");
exit();
?>
