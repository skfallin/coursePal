<?php
require 'includes/connectdb.php';
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
