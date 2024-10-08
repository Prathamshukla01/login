<?php
session_start();

// Database connection details
$dbHost = "tcp:serverbookhives.database.windows.net,1433"; // Azure SQL Server host
$dbUser = "azure"; // Your Azure username
$dbPass = "bookhives@123"; // Your Azure password
$dbName = "bookhivesdb"; // Your Azure database name

try {
    // Create a PDO connection to Azure SQL Database using SQL Server driver
    $conn = new PDO("sqlsrv:server=$dbHost;Database=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$loginMessage = '';

if (isset($_POST['login'])) {
    // Login form submitted
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input (optional but recommended)
    if (!empty($username) && !empty($password)) {
        // Use prepared statements to avoid SQL injection
        $loginQuery = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($loginQuery);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the hashed password
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables and redirect to respective dashboards
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_type'] = $row['user_type'];

                if ($row['user_type'] == 'shopowner') {
                    header("Location: shop.php"); // Redirect to shopowner dashboard
                } elseif ($row['user_type'] == 'customer') {
                    header("Location: cust.php"); // Redirect to customer dashboard
                } else {
                    $loginMessage = 'Invalid user type. Please contact support.';
                }

                exit(); // End script execution after redirection
            } else {
                $loginMessage = 'Incorrect password. Please try again.';
            }
        } else {
            $loginMessage = 'Username not found. Please check your username and try again.';
        }
    } else {
        $loginMessage = 'Please fill in all fields.';
    }
}

$conn = null; // Close the connection
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
            margin-bottom: 20px;
            backdrop-filter: blur(5px);
        }

        h2 {
            text-align: center;
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

        input[type="submit"] {
            background-color: orange;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #ffc107;
        }

        #login-message {
            margin-top: 2px;
            padding: 5px;
            text-align: center;
            color: red; /* Updated to show error in red */
            display: <?php echo empty($loginMessage) ? 'none' : 'block'; ?>;
        }

        #register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <form method="post" action="login.php" autocomplete="off">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" autocomplete="username">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
        
        <input type="submit" name="login" value="Login">

        <div id="login-message"><?php echo $loginMessage; ?></div>
    </form>
    <div id="register-link">Don't have an account? Click here to <a href="register.php">register</a></div>

</body>
</html>
