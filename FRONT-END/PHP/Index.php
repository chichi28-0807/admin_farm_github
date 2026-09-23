<?php
require_once __DIR__ . '/config.php';
if (!isset($pdo) || !($pdo instanceof PDO)) {
    throw new RuntimeException('Database connection is not configured.');
}
$pageTitle = 'Dashboard';
 
$totalChickens = $pdo->query("SELECT COUNT(*) FROM chickens WHERE status='Active'")->fetchColumn();
$totalHens     = $pdo->query("SELECT COUNT(*) FROM chickens WHERE status='Active' AND gender='Hen'")->fetchColumn();
$totalRoosters = $pdo->query("SELECT COUNT(*) FROM chickens WHERE status='Active' AND gender='Rooster'")->fetchColumn();
$eggsToday     = $pdo->query("SELECT COALESCE(SUM(quantity),0) FROM egg_production WHERE log_date = CURDATE()")->fetchColumn();
$eggsThisMonth = $pdo->query("SELECT COALESCE(SUM(quantity),0) FROM egg_production WHERE MONTH(log_date)=MONTH(CURDATE()) AND YEAR(log_date)=YEAR(CURDATE())")->fetchColumn();
$feedStock     = $pdo->query("SELECT COALESCE(SUM(quantity_kg),0) FROM feed_inventory")->fetchColumn();
$salesThisMonth = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM sales WHERE MONTH(sale_date)=MONTH(CURDATE()) AND YEAR(sale_date)=YEAR(CURDATE())")->fetchColumn();
 
include 'header.php';
?>
 
<h2>Farm Overview</h2>
<div class="cards">
    <div class="card">
        <h3><?php echo $totalChickens; ?></h3>
        <p>Active Chickens</p>
    </div>
    <div class="card">
        <h3><?php echo $totalHens; ?> / <?php echo $totalRoosters; ?></h3>
        <p>Hens / Roosters</p>
    </div>
    <div class="card">
        <h3><?php echo $eggsToday; ?></h3>
        <p>Eggs Collected Today</p>
    </div>
    <div class="card">
        <h3><?php echo $eggsThisMonth; ?></h3>
        <p>Eggs This Month</p>
    </div>
    <div class="card">
        <h3><?php echo number_format($feedStock, 1); ?> kg</h3>
        <p>Feed In Stock</p>
    </div>
    <div class="card">
        <h3>$<?php echo number_format($salesThisMonth, 2); ?></h3>
        <p>Sales This Month</p>
    </div>
</div>
 
<h2>Recent Egg Logs</h2>
<table>
    <tr><th>Date</th><th>Coop</th><th>Quantity</th><th>Broken</th></tr>
    <?php
    $recent = $pdo->query("SELECT * FROM egg_production ORDER BY log_date DESC LIMIT 5");
    foreach ($recent as $row):
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['log_date']); ?></td>
        <td><?php echo htmlspecialchars($row['coop_location']); ?></td>
        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
        <td><?php echo htmlspecialchars($row['broken_count']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>
 