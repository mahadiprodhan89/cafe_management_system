<?php
$pageTitle = 'Manage Items';
require_once '../includes/header.php';
require_once '../config/database.php';
requireAdmin();

$conn = getDBConnection();
$message = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Item deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-error">Error deleting item.</div>';
    }
    $stmt->close();
}

// Get all items
$items = $conn->query("SELECT * FROM items ORDER BY id DESC");
?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="card-title">Manage Items</h2>
        <a href="add_item.php" class="btn btn-success">Add New Item</a>
    </div>
    
    <?php echo $message; ?>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items->num_rows > 0): ?>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td>৳<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['is_available'] ? '<span style="color: green;">Available</span>' : '<span style="color: red;">Unavailable</span>'; ?></td>
                            <td>
                                <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>

