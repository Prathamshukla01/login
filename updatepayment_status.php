<?php
include "config.php";
include "shopboiler.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $newPaymentStatus = $_POST['new_payment_status'];

    // Update payment status in custorders table
    $updatePaymentStatusQuery = "UPDATE custorders SET payment_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updatePaymentStatusQuery);

    if ($stmt !== false) {
        $stmt->bind_param("si", $newPaymentStatus, $orderId);

        if ($stmt->execute()) {
            echo 'Payment status updated successfully!';
        } else {
            echo 'Error updating payment status: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Error preparing statement: ' . $conn->error;
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
