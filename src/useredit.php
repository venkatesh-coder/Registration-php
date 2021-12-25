<?php
session_start();
if  (isset($_SESSION['user_session'])) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli("localhost", "venkatesh", "123456", "test");

    $sql = "SELECT * FROM registered_users";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    echo "Login successful<br>";
    var_dump($row);
}
else {
    echo "Please login";
}
?>
