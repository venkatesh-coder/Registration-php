<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="" method="POST">
            <label for="email">
                E-mail:
                <input type="email" name="email" id="email" placeholder="Enter E-mail address:" required>
            </label>
            <br>
            <label for="password">
                Phone number:
                <input type="password" name="password" id="password" placeholder="Enter password:" required>
            </label>
            <br>
            <input type="submit" name="submit" value="Login">
        </form>

<?php
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli("localhost", "venkatesh", "123456", "test");

    $query = "SELECT id, password_hash FROM registered_users where email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    /* var_dump($row); */
    if (isset($row['password_hash'])) {
        if (password_verify($password, $row['password_hash'])) {
            @session_start();
            $_SESSION['user_session'] = $row['id'];
            header("Location: useredit.php");
            return true;
        }
        else {
            echo "Invalid password";
            exit();
        }
    }
    echo "No account is associated with this mail addresss, you Register";
    $mysqli->close();
}
?>
    </body>
</html>
