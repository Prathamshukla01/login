<?php
include('shopboiler.php');
// Database connection details
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

        .title{
            text-align:center;
            text-transform:uppercase;
            color:black;
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
            font-size:1.5rem;
            color:black;
        }

        p {      
            margin-top: 1.5rem;
            padding:1rem;
            font-size:1rem;
            
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
                $select_completed = mysqli_query($conn, "SELECT total_price FROM custorders WHERE payment_status='completed' && username='$username'") or die('Query failed');

                if (mysqli_num_rows($select_completed) > 0) {
                    while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['total_price'];
                        $total_completed += $total_price;
                    }
                }
                ?>
                <h3><?php echo $total_completed; ?></h3>
                <p>Completed payments</p>
            </div>
            
            <div class="box">
                <?php
                
                $select_pending = mysqli_query($conn, "SELECT * FROM custorders WHERE payment_status='pending' && shopowner_username='$username'") or die('Query failed');
                $number_of_pendings=mysqli_num_rows($select_pending);
                
                ?>
                <h3><?php echo $number_of_pendings; ?></h3>
                <p>Total Pending orders</p>
            </div>

            <div class="box">
                <?php
                
                    $select_orders = mysqli_query($conn, "SELECT * FROM custorders WHERE username = '$username' && payment_status='confirmed'") or die('query failed');
                    $number_of_orders=mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Completed Orders</p>
            </div>

            <div class="box">
                <?php
                    $select_books = mysqli_query($conn, "SELECT * FROM books WHERE username= '$username'") or die('query failed');
                    $number_of_books=mysqli_num_rows($select_books);
                ?>
                <h3><?php echo $number_of_books; ?></h3>
                <p>Books added</p>
            </div>

           <!-- <div class="box">
                <//?php
                    $select_messages = mysqli_query($conn, "SELECT * FROM message WHERE username= '$username'") or die('query failed');
                    $number_of_messages=mysqli_num_rows($select_messages);
                ?>
                <h3><//?php echo $number_of_messages; ?></h3>
                <p>New messages</p>
            </div>-->
            
            <div class="box">
                <?php
                // Count books that are out of stock
                $select_out_of_stock = mysqli_query($conn, "SELECT * FROM books WHERE availability='Out of Stock' && username='$username'")or die('query failed');;
                $out_of_stock_count=mysqli_num_rows($select_out_of_stock);
            
                ?>
                <h3><?php echo $out_of_stock_count; ?></h3>
                <p>Out of Stock Books</p>
            </div>
            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pending = mysqli_query($conn, "SELECT total_price FROM custorders WHERE payment_status='pending' && shopowner_username='$username'") or die('Query failed');

                if (mysqli_num_rows($select_pending) > 0) {
                    while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                        $total_price = $fetch_pendings['total_price'];
                        $total_pendings += $total_price;
                    }
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
$conn->close();
?>
 