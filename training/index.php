<?php //-->
date_default_timezone_set('Asia/Manila');
include("control.php");

Control::getInstance($_SERVER["REQUEST_URI"])->output();
