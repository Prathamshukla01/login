<?php
/*include('shopboiler.php');*/
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

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .dashboard {
            width: 80%;
            max-width: 1200px;
        }

        .title {
            text-align: center;
            text-transform: uppercase;
            color: black;
            font-size: 2rem;
        }
        
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            grid-gap: 20px;
            margin-top: 20px;
            padding: 2rem;
        }

        .box {
            background-color: whitesmoke;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h3 {
            margin: 0;
            font-size: 1.5rem;
            color: black;
        }

        p {      
            margin-top: 1.5rem;
            padding: 1rem;
            font-size: 1rem;
            color: orange;
            background-color: #e2e5de;  
        }

        @media (min-width: 768px) {
            .box-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>
    <section class="dashboard">
        <h1 class="title">Dashboard</h1>
        <div class="box-container">
           
           <div class="box">
                <?php
                $total_completed = 0;
                $select_completed = $conn->prepare("SELECT total_price FROM custorders WHERE payment_status='completed' AND username=:username");
                $select_completed->bindParam(':username', $username);
                $select_completed->execute();

                while ($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)) {
                    $total_completed += $fetch_completed['total_price'];
                }
                ?>
                <h3><?php echo number_format($total_completed, 2); ?> &#8377</h3>
                <p>Completed payments</p>
            </div>
            
            <div class="box">
                <?php
                $select_pending = $conn->prepare("SELECT * FROM custorders WHERE payment_status='pending' AND shopowner_username=:username");
                $select_pending->bindParam(':username', $username);
                $select_pending->execute();
                $number_of_pendings = $select_pending->rowCount();
                ?>
                <h3><?php echo $number_of_pendings; ?></h3>
                <p>Total Pending orders</p>
            </div>

            <div class="box">
                <?php
                $select_orders = $conn->prepare("SELECT * FROM custorders WHERE username=:username AND payment_status='confirmed'");
                $select_orders->bindParam(':username', $username);
                $select_orders->execute();
                $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Completed Orders</p>
            </div>

            <div class="box">
                <?php
                $select_books = $conn->prepare("SELECT * FROM books WHERE username=:username");
                $select_books->bindParam(':username', $username);
                $select_books->execute();
                $number_of_books = $select_books->rowCount();
                ?>
                <h3><?php echo $number_of_books; ?></h3>
                <p>Books added</p>
            </div>

            <div class="box">
                <?php
                // Count books that are out of stock
                $select_out_of_stock = $conn->prepare("SELECT * FROM books WHERE availability='Out of Stock' AND username=:username");
                $select_out_of_stock->bindParam(':username', $username);
                $select_out_of_stock->execute();
                $out_of_stock_count = $select_out_of_stock->rowCount();
                ?>
                <h3><?php echo $out_of_stock_count; ?></h3>
                <p>Out of Stock Books</p>
            </div>
            
            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pending = $conn->prepare("SELECT total_price FROM custorders WHERE payment_status='pending' AND shopowner_username=:username");
                $select_pending->bindParam(':username', $username);
                $select_pending->execute();

                while ($fetch_pendings = $select_pending->fetch(PDO::FETCH_ASSOC)) {
                    $total_pendings += $fetch_pendings['total_price'];
                }
                ?>
                <h3><?php echo number_format($total_pendings, 2); ?> &#8377</h3>
                <p>Total Amount Pendings</p>
            </div>
            
        </div>
    </section>
</body>
</html>

<?php
$conn = null; // Close the connection
?>
