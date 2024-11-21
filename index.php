<?php
include 'database.php';

$sql = "SELECT id, name, email, phone, position, image FROM employees";
$result = $conn->query($sql);

if (isset($_GET['message'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        img { max-width: 100px; height: auto; border-radius: 5px; }
        a { text-decoration: none; color: white; padding: 5px 10px; border-radius: 5px; }
        .add-btn { background-color: #28a745; }
        .edit-btn { background-color: #ffc107; }
        .delete-btn { background-color: #dc3545; }
    </style>
</head>
<body>
    <h1>Employee List</h1>
    <a href="create.php" class="add-btn">Add New Employee</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><?= htmlspecialchars($row['position']); ?></td>
                        <td><img src="data:image/jpeg;base64,<?= base64_encode($row['image']); ?>" alt="Employee Image"></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete.php?id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No employees found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
