<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $image = !empty($_FILES['image']['tmp_name']) ? file_get_contents($_FILES['image']['tmp_name']) : null;

    if ($id) {
        $stmt = $conn->prepare("UPDATE images SET name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sbi", $name, $image, $id);
        $stmt->send_long_data(1, $image);
    } else {
        $stmt = $conn->prepare("INSERT INTO images (name, image) VALUES (?, ?)");
        $stmt->bind_param("sb", $name, $image);
        $stmt->send_long_data(1, $image);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: crud.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: crud.php");
    exit;
}

$records = $conn->query("SELECT * FROM images");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD with Image Blob</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 100%;
        }
        h2 {
            text-align: center;
        }
        form {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
        }
        .action-btns a {
            display: inline-block;
            margin-right: 5px;
            padding: 5px 10px;
            color: white;
            background: #007BFF;
            text-decoration: none;
            border-radius: 4px;
        }
        .action-btns a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h2>PHP CRUD with Image Blob</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?= $_GET['edit'] ?? '' ?>">
        <input type="text" name="name" placeholder="Enter Name" required value="<?= $_GET['edit_name'] ?? '' ?>">
        <input type="file" name="image" accept="image/*" <?= isset($_GET['edit']) ? '' : 'required' ?>>
        <button type="submit"><?= isset($_GET['edit']) ? 'Update' : 'Save' ?></button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $records->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="Image"></td>
                    <td class="action-btns">
                        <a href="?edit=<?= $row['id'] ?>&edit_name=<?= htmlspecialchars($row['name']) ?>">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
