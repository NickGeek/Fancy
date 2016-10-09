<?php
include_once('DashboardHandler.php');
if (empty($_GET['blog'])) { echo "Not enough data has been sent"; exit(); }
$handler = new DashboardHandler();

echo $handler->getPosts($_GET['blog']);