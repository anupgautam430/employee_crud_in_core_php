<?php
include 'database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $image = file_get_contents($_FILES['image']['tmp_name']);

    $sql = "INSERT INTO employees (name, email, phone, position, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $position, $image);

    if ($stmt->execute()) {
        header("Location: index.php?message=Employee+created+successfully");
        $message = "Employee added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
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
    <h1>Add New Employee</h1>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position" required>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit">Add Employee</button>
    </form>
</body>
</html>
