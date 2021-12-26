<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
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
        input[type=text], input[type=password],
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
    <li><a href="./login.php">Login</a></li>
<ul>
</nav>
        <h1 style="padding-top: 10px;">Register now</h1>
        <form action="" method="POST">
            <label for="f_name">
                <b>First name:</b>
                <input type="text" name="f_name" id="f_name" placeholder="Enter first name:" required>
            </label>
            <br>
            <label for="l_name">
                <b>Last name:</b>
                <input type="text" name="l_name" id="l_name"placeholder="Enter last name:" required>
            </label>
            <br>
            <label for="email">
                <b>E-mail:</b>
                <input type="email" name="email" id="email" placeholder="Enter E-mail address:" required>
            </label>
            <br>
            <label for="ph_num">
                <b>Phone number:</b>
                <input type="text" name="ph_num" id="ph_num" placeholder="Enter Phone number:" required>
            </label>
            <br>
            <input type="submit" name="submit" class="submit" value="Register">
        </form>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$upload_dir = "uploads/pro_pics/";
$default_pic_path = $upload_dir . "default.png";

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['submit'])) {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $ph_num = $_POST['ph_num'];
    $password = generateRandomString(12);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli("localhost", "venkatesh", "123456", "test");
    $query = "SELECT id FROM registered_users where email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (isset($row['id']))
    {
        echo "<br>Already registered<br>";
        $mysqli->close();
        exit();
    }

    $query = "INSERT INTO registered_users(f_name, l_name, phone, email, "
        . "password_hash, p_pic_path) VALUES(?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssss", $f_name, $l_name, $ph_num, $email,
        $hash, $default_pic_path);
    $stmt->execute();
    $mysqli->close();

    // sending password via mail
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP(); //Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail_addr = getenv('MAIL_ADDR');
        $mail_password = getenv('MAIL_PASSWD');
        if (!isset($mail_addr) || !isset($mail_password))
        {
            fwrite(STDERR, "Error: export mail address and password.\n");
            exit(1);
        }
        $mail->Username   = $mail_addr; //SMTP username
        $mail->Password   = $mail_password; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom($mail_addr, "XYZ corp");
        $mail->addAddress($email, $f_name);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "XYZ corp. Registration successful";
        $mail->Body    = "Hi, $f_name, Your account has been successfully created, your password is <b>$password</b>";
        $mail->AltBody = "Hi, $f_name, Your account has been successfully created, your password is $password";

        $mail->send();
        echo 'Your password has been sent to the above mentioned mail address<br>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    echo "Registration successfull";
}
?>
    </body>
</html>
