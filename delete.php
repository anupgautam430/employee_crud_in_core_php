<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?message=Employee+deleted+successfully");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}
?>
