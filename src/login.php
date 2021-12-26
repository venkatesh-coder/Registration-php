<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html {
            position: relative;
        }
        body {
            background: url('greek-vase.png');
            display: flex;
            height: 100%;
            flex-direction: column;
            background-repeat: repeat;
            background-size: 15.62em 15.62em;
            font-family: "Montserrat", sans-serif;
            scroll-behavior: smooth;
        }
        nav {
            padding: 20px;
            margin: 0px;
            background-color: #343334;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            padding: 13px;
            margin: 10px;
            background-color: #777;
            display: inline-block;
            text-align: center;
        }
        nav  li a {
            color: yellow;
            text-decoration: none;
        }
        input[type=password],
        input[type=email]
        {
          width: 100%;
          padding: 15px;
          margin: 5px 0 22px 0;
          display: inline-block;
          border: none;
          background: #f1f1f1;
        }
        .submit {
              background-color: #04AA6D;
              color: white;
              padding: 16px 20px;
              margin: 8px 0;
              border: none;
              cursor: pointer;
              width: 100%;
              opacity: 0.9;
        }
    </style>
    <body>

<nav>
<ul >
    <li><a href="./index.php">Home</a></li>
    <li><a href="./register.php">Register</a></li>
<ul>
</nav>
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
            <input type="submit" name="submit" class="submit" value="Login">
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
    echo "No account is associated with this mail addresss, you can Register";
    $mysqli->close();
}
?>
    </body>
</html>
