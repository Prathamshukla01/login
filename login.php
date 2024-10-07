<?php
session_start();

$dbHost = "Localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginMessage = '';

if (isset($_POST['login'])) {
    // Login form submitted
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($loginQuery);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables and redirect to respective dashboards
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_type'] = $row['user_type'];

                if ($row['user_type'] == 'shopowner') {
                    header("Location: shop.php");
                    // Redirect to shopowner dashboard
                } elseif ($row['user_type'] == 'customer') {
                    header("Location: cust.php");
                    // Redirect to customer dashboard
                } else {
                    $loginMessage = 'Invalid user type. Please contact support.';
                }

                exit();
            } else {
                $loginMessage = 'Incorrect password. Please try again.';
            }
        } else {
            $loginMessage = 'Username not found. Please check your username and try again.';
        }
    } else {
        $loginMessage = 'Error in the login query: ' . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            overflow: hidden; /* Prevent scrolling on smaller screens */
            
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 50%;
            margin-bottom:20px;
            backdrop-filter: blur(5px);
        }

        h2{
            text-align:center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 2px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: orange;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover{
            background-color: #ffc107;
        }

        #login-message {
            margin-top: 2px;
            padding: 5px;
            text-align: center;
            color: #3c763d;
            display: <?php echo empty($loginMessage) ? 'none' : 'block'; ?>;
        }
        #register-link{
            text-align: center; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>

    <form method="post" action="login.php" autocomplete="off">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"autocomplete="username">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
        
        <input type="submit" name="login" value="Login">

        <div id="login-message"><?php echo $loginMessage; ?></div>
    </form>
    <div id="register-link">Don't have an account? Click here to <a href="register.php">register</a>
    </div>

</body>
</html>
