<?php
session_start();

// Database connection details
$dbHost = "tcp:serverbookhives.database.windows.net,1433"; // Azure SQL Server host
$dbUser = "azure"; // Your Azure username
$dbPass = "bookhives@123"; // Your Azure password
$dbName = "bookhivesdb"; // Your Azure database name

try {
    // Create a PDO connection
    $conn = new PDO("sqlsrv:server=$dbHost;Database=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize registration message
$registrationMessage = '';

if (isset($_POST['register'])) {
    // Registration form submitted
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);

    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $registrationMessage = 'Username already exists. Please choose a different username.';
    } else {
        $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $registrationMessage = 'Email address already exists. Please use a different email address.';
        } else {
            // Continue with registration process
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];

            if (strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
                $registrationMessage = 'Password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one digit.';
            } elseif ($cpassword !== $password) {
                $registrationMessage = 'Confirm password not matched!';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $userType = $_POST['user_type'];
                $firstName = htmlspecialchars($_POST['first_name']);
                $lastName = htmlspecialchars($_POST['last_name']);
                $phone = htmlspecialchars($_POST['phone']);
                $address = htmlspecialchars($_POST['address']);
                $city = htmlspecialchars($_POST['city']);
                $state = htmlspecialchars($_POST['state']);
                $pincode = htmlspecialchars($_POST['pincode']);
                $shopName = htmlspecialchars($_POST['shop_name']);
                $shopLocation = htmlspecialchars($_POST['shop_location']);

                $insertQuery = "INSERT INTO users (username, password, user_type, first_name, last_name, phone, address, city, state, pincode, email, shop_name, shop_location) 
                                VALUES (:username, :password, :user_type, :first_name, :last_name, :phone, :address, :city, :state, :pincode, :email, :shop_name, :shop_location)";
                $stmt = $conn->prepare($insertQuery);

                if ($stmt->execute([
                    ':username' => $username,
                    ':password' => $passwordHash,
                    ':user_type' => $userType,
                    ':first_name' => $firstName,
                    ':last_name' => $lastName,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':city' => $city,
                    ':state' => $state,
                    ':pincode' => $pincode,
                    ':email' => $email,
                    ':shop_name' => $shopName,
                    ':shop_location' => $shopLocation
                ])) {
                    $registrationMessage = "Registration successful. You can now log in.";
                    header("Location: login.php");
                    exit();
                } else {
                    $registrationMessage = "Error: Could not register the user.";
                }
            }
        }
    }
}

$_SESSION['registrationMessage'] = $registrationMessage;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 50%;
            margin: 20px auto;
        }

        form h2 {
            text-align: center;
            margin-top: 2px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input, form select {
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

        #registration-message {
            margin-top: 2px;
            padding: 5px;
            text-align: center;
            color: #3c763d;
            display: <?php echo empty($registrationMessage) ? 'none' : 'block'; ?>;
        }

        #login-link {
            text-align: center;
        }
    </style>
</head>
<body>

<form method="post" action="register.php" autocomplete="off">
    <h2> Registration </h2>
    <!-- Common fields -->
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required autocomplete="on">

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required value="<?= isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" autocomplete="on">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" autocomplete="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required autocomplete="new-password">

    <label for="cpassword">Confirm Password:</label>
    <input type="password" id="cpassword" name="cpassword" required autocomplete="new-password">

    <label for="user_type">User Type:</label>
    <select id="user_type" name="user_type" required>
        <option value="">--Select--</option>
        <option value="customer" <?= (isset($_POST['user_type']) && $_POST['user_type'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
        <option value="shopowner" <?= (isset($_POST['user_type']) && $_POST['user_type'] === 'shopowner') ? 'selected' : ''; ?>>Shop Owner</option>
    </select>

    <label for="phone">Phone:</label>
    <input type="tel" id="phone" name="phone" autocomplete="tel" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" autocomplete="street-address" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
    <input type="text" name="city" autocomplete="address-level2" value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
    <input type="text" name="state" autocomplete="address-level1" value="<?= isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>">
    <input type="text" name="pincode" autocomplete="postal-code" value="<?= isset($_POST['pincode']) ? htmlspecialchars($_POST['pincode']) : ''; ?>">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" autocomplete="email" required>

    <!-- Shop owner fields -->
    <div id="shopowner_fields" style="display:none;">
        <label for="shop_name">Shop Name:</label>
        <input type="text" id="shop_name" name="shop_name" value="<?= isset($_POST['shop_name']) ? htmlspecialchars($_POST['shop_name']) : ''; ?>">
        <br>
        <label for="shop_location">Shop Location:</label>
        <input type="text" id="shop_location" name="shop_location" value="<?= isset($_POST['shop_location']) ? htmlspecialchars($_POST['shop_location']) : ''; ?>">
        <br>
    </div>

    <input type="submit" name="register" value="Register">
    <div id="registration-message"><?= isset($_SESSION['registrationMessage']) ? $_SESSION['registrationMessage'] : ''; ?></div>
</form>

<div id="login-link">
    Already have an account? <a href="login.php">login</a>
</div>

<!-- JavaScript to toggle visibility of fields based on user type -->
<script>
    document.getElementById('user_type').addEventListener('change', function () {
        var shopownerFields = document.getElementById('shopowner_fields');

        if (this.value === 'shopowner') {
            shopownerFields.style.display = 'block';
        } else {
            shopownerFields.style.display = 'none';
        }
    });
</script>

</body>
</html>
