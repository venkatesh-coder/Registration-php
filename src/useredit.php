<?php
$upload_dir = "uploads/pro_pics/";
$default_pic_path = $upload_dir . "default.png";
session_start();
if  (isset($_SESSION['user_session'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli("localhost", "venkatesh", "123456", "test");
        $id = $_SESSION['user_session'];

        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $about_you = $_POST['about_you'];
        $filepath = NULL;

        $sql = "SELECT p_pic_path FROM registered_users where id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $req_row = $result->fetch_assoc();
        $pic_path = $req_row['p_pic_path'];


        if ($_FILES['imgfile']['tmp_name'] !== '')
        /* { */
        /*     if (isset($pic_path)) { */
        /*         $filepath = $pic_path; */
        /*     } */
        /*     else { */
        /*         $filepath = $default_pic_path; */
        /*     } */
        /* } */
        /* else */
        {
            $img_properties = getimagesize($_FILES['imgfile']['tmp_name']);
            if ($img_properties === false) {
                echo "Error: Upload a valid image";
            }
            if ($_FILES['imgfile']['size']  > 512000) {
                echo "Error: Large file, file size should be less than 500kb";
            }
            $ext = strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_EXTENSION));
            $valid_exts = array("jpg", "png", "jpeg", "bmp");
            if (!in_array($ext, $valid_exts)) {
                echo "Error: Invalid file format";
            }

            $filepath = $upload_dir . rand(100000, 999999). '_' . time() . "." . $ext;
            if (!move_uploaded_file($_FILES['imgfile']['tmp_name'], $filepath)) {
                echo "Error: Unable to upload, try again later";
            }
        }
        else {
            $filepath = $pic_path;
        }


        $sql = "UPDATE registered_users SET f_name=?, l_name=?, phone=?, "
            . "p_pic_path=?, about_you=? where id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssi", $f_name, $l_name, $phone, 
            $filepath, $about_you, $id);
        $stmt->execute();
        echo "Profile updated successfully";
        header("Location: useredit.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change/Edit profile</title>
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
        .pic-label img {
            display: block;
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .pic-label {
            display: block;
            text-align: center;
        }
        .img-cont
        {
            margin: 1rem;
            vertical-align: center;
            text-align: center;
            position: relative;
            top: 30px;
        }
        .browse-pp {
            display: none;
        }
        .pic-label {
            position: absolute;
            top:50%;
            left:47%;
            margin-top:-25px;
            margin-left:-25px;
            display: block;
        }
        .profile-pic {
            cursor: pointer;
        }
        form {
            display: block;
        }
    </style>

</head>
<body>
<?php
if  (isset($_SESSION['user_session'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli("localhost", "venkatesh", "123456", "test");
        $id = $_SESSION['user_session'];
        $sql = "SELECT f_name, l_name, email, phone, p_pic_path, about_you "
            . "FROM registered_users where id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $req_row = $result->fetch_assoc();
        $f_name = $req_row['f_name'];
        $l_name = $req_row['l_name'];
        $email = $req_row['email'];
        $phone = $req_row['phone'];
        $pic_path = $req_row['p_pic_path'];
        $about_you = $req_row['about_you'];
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
<div class="img-cont">
<label for="upload-pic" class="pic-label">
<?php
echo "<img src=\"$pic_path\" class=\"change-pointer\" alt=\"Profile photo\">";
?>
    <input type="file" name="imgfile" id="upload-pic" class="browse-pp">
</label>
</div>
            <label for="f_name" style="margin-top: 200px; display: block;">
                First name:
                <input type="text" name="f_name" id="f_name" value="<?php echo $f_name;?>" placeholder="Enter first name:" required>
            </label>
            <br>
            <label for="l_name">
                Last name:
                <input type="text" name="l_name" id="l_name" value="<?php echo $l_name;?>" placeholder="Enter last name:" required>
            </label>
            <br>
            <label for="email">
                E-mail:
                <input type="email" name="email" id="email" value="<?php echo $email;?>" placeholder="Enter E-mail address:" required>
            </label>
            <br>
            <label for="phone">
                Phone number:
                <input type="text" name="phone" id="phone" value="<?php echo $phone;?>" placeholder="Enter Phone number:" required>
            </label>
            <label for="about_you">
                About me:
                <input type="text" name="about_you" id="about_you" value="<?php echo $about_you;?>" placeholder="Tell us about yourself:" required>
            </label>
            <br>
            <input type="submit" name="update" class="submit" value="Update">
        </form>
<nav>
<ul >
    <li><a href="./index.php">Go back to Home</a></li>
    <li><a href="./logout.php">Logout</a></li>
<ul>
</body>
</html>
