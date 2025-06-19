<?php
// Simple PHP CRUD with MySQLi (procedural)
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'simple_crud';

// Connect to MySQL
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
// Create database if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
mysqli_select_db($conn, $dbname);
// Create table if not exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS items (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL)");

// Handle Create
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "INSERT INTO items (name) VALUES ('$name')");
    header('Location: index.php');
    exit;
}
// Handle Update
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "UPDATE items SET name = '$name' WHERE id = $id");
    header('Location: index.php');
    exit;
}
// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM items WHERE id = $id");
    header('Location: index.php');
    exit;
}
// Fetch all items
$result = mysqli_query($conn, "SELECT * FROM items");
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
// Fetch item for editing
$editItem = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
    $editItem = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple PHP CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 400px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        form { margin-bottom: 20px; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Simple PHP CRUD</h1>
    <?php if ($editItem): ?>
        <h2>Edit Item</h2>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($editItem['id']) ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($editItem['name']) ?>" required>
            <button type="submit" name="update">Update</button>
            <a href="index.php">Cancel</a>
        </form>
    <?php else: ?>
        <h2>Add Item</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Item name" required>
            <button type="submit" name="add">Add</button>
        </form>
    <?php endif; ?>
    <h2>Items List</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['id']) ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $item['id'] ?>">Edit</a>
                    <a href="?delete=<?= $item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html> 