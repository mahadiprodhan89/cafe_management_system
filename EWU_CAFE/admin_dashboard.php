<?php
$pageTitle = 'Admin Dashboard';
require_once '../includes/header.php';
require_once '../config/database.php';
requireAdmin();

$conn = getDBConnection();

// Get statistics
$totalItems = $conn->query("SELECT COUNT(*) as count FROM items")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE status = 'completed'")->fetch_assoc()['total'];

// Recent orders
$recentOrders = $conn->query("
    SELECT o.*, u.full_name, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC 
    LIMIT 10
");
?>
<div class="card">
    <h2 class="card-title">Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3><?php echo $totalItems; ?></h3>
        <p>Total Items</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $totalOrders; ?></h3>
        <p>Total Orders</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $pendingOrders; ?></h3>
        <p>Pending Orders</p>
    </div>
    <div class="stat-card">
        <h3><?php echo $totalUsers; ?></h3>
        <p>Total Users</p>
    </div>
    <div class="stat-card">
        <h3>৳<?php echo number_format($totalRevenue, 2); ?></h3>
        <p>Total Revenue</p>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Recent Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recentOrders->num_rows > 0): ?>
                    <?php while ($order = $recentOrders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                            <td>৳<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span style="padding: 5px 10px; border-radius: 4px; background-color: #f39c12; color: white;"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></td>
                            <td><a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No orders found.</td>
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

