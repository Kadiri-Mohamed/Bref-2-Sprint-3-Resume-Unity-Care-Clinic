<?php
require_once '../config/database.php';
require_once '../models/User.php';

Session::destroy();
echo "<script>location.href = 'login.php';</script>";

