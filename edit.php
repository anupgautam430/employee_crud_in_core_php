<?php
include 'database.php';

$message = '';
$employee = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Employee not found!";
        exit;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $image = !empty($_FILES['image']['tmp_name']) ? file_get_contents($_FILES['image']['tmp_name']) : null;

    if ($image) {
        $sql = "UPDATE employees SET name = ?, email = ?, phone = ?, position = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $position, $image, $id);
    } else {
        $sql = "UPDATE employees SET name = ?, email = ?, phone = ?, position = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $position, $id);
    }
    if ($stmt->execute()) {
        header("Location: index.php?message=Employee+deleted+successfully");
        $message = "Employee updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    } else {
    echo "Invalid request!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        form { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        input, textarea, select, button { display: block; width: 100%; margin-bottom: 15px; padding: 10px; }
        button { background-color: #007BFF; color: white; border: none; }
        button:hover { background-color: #0056b3; cursor: pointer; }
        .message { margin-bottom: 15px; color: green; }
    </style>
</head>
<body>
    <h1>Edit Employee</h1>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <input type="hidden" name="id" value="<?= $employee['id']; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($employee['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($employee['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($employee['phone']); ?>" required>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position" value="<?= htmlspecialchars($employee['position']); ?>" required>

        <label for="image">Image (leave empty to keep current):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Update Employee</button>
    </form>
</body>
</html>
