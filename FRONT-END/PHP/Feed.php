<?php
require 'config.php';
if (!isset($pdo) || !($pdo instanceof PDO)) {
    throw new RuntimeException('Database connection is not configured.');
}
$pageTitle = 'Feed Inventory';
 
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM feed_inventory WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: feed.php');
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['feed_type']);
    $qty = (float)$_POST['quantity_kg'];
    $cost = $_POST['cost'] !== '' ? (float)$_POST['cost'] : null;
    $added = $_POST['date_added'];
    $expiry = $_POST['expiry_date'] ?: null;
 
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE feed_inventory SET feed_type=?, quantity_kg=?, cost=?, date_added=?, expiry_date=? WHERE id=?");
        $stmt->execute([$type, $qty, $cost, $added, $expiry, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO feed_inventory (feed_type, quantity_kg, cost, date_added, expiry_date) VALUES (?,?,?,?,?)");
        $stmt->execute([$type, $qty, $cost, $added, $expiry]);
    }
    header('Location: feed.php');
    exit;
}
 
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM feed_inventory WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRow = $stmt->fetch();
}
 
$stock = $pdo->query("SELECT * FROM feed_inventory ORDER BY date_added DESC")->fetchAll();
 
include 'header.php';
?>
 
<h2><?php echo $editRow ? 'Edit Feed Entry' : 'Add Feed Stock'; ?></h2>
<form method="POST" class="form-grid">
    <input type="hidden" name="id" value="<?php echo $editRow['id'] ?? ''; ?>">
 
    <label>Feed Type
        <input type="text" name="feed_type" required placeholder="e.g. Layer Mash" value="<?php echo htmlspecialchars($editRow['feed_type'] ?? ''); ?>">
    </label>
 
    <label>Quantity (kg)
        <input type="number" step="0.01" name="quantity_kg" required value="<?php echo $editRow['quantity_kg'] ?? ''; ?>">
    </label>
 
    <label>Cost ($)
        <input type="number" step="0.01" name="cost" value="<?php echo $editRow['cost'] ?? ''; ?>">
    </label>
 
    <label>Date Added
        <input type="date" name="date_added" required value="<?php echo $editRow['date_added'] ?? date('Y-m-d'); ?>">
    </label>
 
    <label>Expiry Date
        <input type="date" name="expiry_date" value="<?php echo $editRow['expiry_date'] ?? ''; ?>">
    </label>
 
    <button type="submit"><?php echo $editRow ? 'Update' : 'Add'; ?> Feed</button>
    <?php if ($editRow): ?><a href="feed.php" class="btn-cancel">Cancel</a><?php endif; ?>
</form>
 
<h2>Feed Stock</h2>
<table>
    <tr><th>Type</th><th>Quantity (kg)</th><th>Cost</th><th>Added</th><th>Expiry</th><th>Actions</th></tr>
    <?php foreach ($stock as $f): ?>
    <tr>
        <td><?php echo htmlspecialchars($f['feed_type']); ?></td>
        <td><?php echo htmlspecialchars($f['quantity_kg']); ?></td>
        <td><?php echo $f['cost'] !== null ? '$' . number_format($f['cost'], 2) : '-'; ?></td>
        <td><?php echo htmlspecialchars($f['date_added']); ?></td>
        <td><?php echo htmlspecialchars($f['expiry_date']); ?></td>
        <td>
            <a href="feed.php?edit=<?php echo $f['id']; ?>">Edit</a> |
            <a href="feed.php?delete=<?php echo $f['id']; ?>" onclick="return confirm('Delete this entry?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>
 