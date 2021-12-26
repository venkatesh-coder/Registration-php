<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
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
    </style>

</head>
<body>
<!-- <form action="./register"></form> -->
<nav>
<ul >
    <li><a href="./register.php">Register</a></li>
    <li><a href="./login.php">Login</a></li>
<ul>
</nav>
    <h1>Welcome, you can Register or sign-in</h1>
</body>
</html>
