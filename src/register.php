<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
    </head>
    <body>
        <h1>Register now</h1>
        <form action="" method="POST">
            <label for="f_name">
                First name:
                <input type="text" name="f_name" id="f_name" placeholder="Enter first name:" required>
            </label>
            <br>
            <label for="l_name">
                Last name:
                <input type="text" name="l_name" id="l_name"placeholder="Enter last name:" required>
            </label>
            <br>
            <label for="email">
                E-mail:
                <input type="email" name="email" id="email" placeholder="Enter E-mail address:" required>
            </label>
            <br>
            <label for="ph_num">
                Phone number:
                <input type="tel" name="ph_num" id="ph_num" placeholder="Enter Phone number:" required>
            </label>
            <br>
            <input type="submit" name="submit" value="Register">
        </form>
        <a href="login.php">Login</a>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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

    $query = "INSERT INTO registered_users(f_name, l_name, phone, email, password_hash) "
        ."VALUES(?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssss", $f_name, $l_name, $ph_num, $email, $hash);
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
