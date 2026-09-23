<?php
require 'config.php';
/** @var PDO $pdo */
$pageTitle = 'Egg Production';
 
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM egg_production WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: eggs.php');
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['log_date'];
    $coop = trim($_POST['coop_location']);
    $qty = (int)$_POST['quantity'];
    $broken = (int)$_POST['broken_count'];
    $notes = trim($_POST['notes']);
 
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE egg_production SET log_date=?, coop_location=?, quantity=?, broken_count=?, notes=? WHERE id=?");
        $stmt->execute([$date, $coop, $qty, $broken, $notes, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO egg_production (log_date, coop_location, quantity, broken_count, notes) VALUES (?,?,?,?,?)");
        $stmt->execute([$date, $coop, $qty, $broken, $notes]);
    }
    header('Location: eggs.php');
    exit;
}
 
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM egg_production WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRow = $stmt->fetch();
}
 
$logs = $pdo->query("SELECT * FROM egg_production ORDER BY log_date DESC")->fetchAll();
 
include 'header.php';
?>
 
<h2><?php echo $editRow ? 'Edit Egg Log' : 'Log Egg Collection'; ?></h2>
<form method="POST" class="form-grid">
    <input type="hidden" name="id" value="<?php echo $editRow['id'] ?? ''; ?>">
 
    <label>Date
        <input type="date" name="log_date" required value="<?php echo $editRow['log_date'] ?? date('Y-m-d'); ?>">
    </label>
 
    <label>Coop Location
        <input type="text" name="coop_location" value="<?php echo htmlspecialchars($editRow['coop_location'] ?? ''); ?>">
    </label>
 
    <label>Quantity Collected
        <input type="number" name="quantity" min="0" required value="<?php echo $editRow['quantity'] ?? 0; ?>">
    </label>
 
    <label>Broken Count
        <input type="number" name="broken_count" min="0" value="<?php echo $editRow['broken_count'] ?? 0; ?>">
    </label>
 
    <label class="full-width">Notes
        <textarea name="notes"><?php echo htmlspecialchars($editRow['notes'] ?? ''); ?></textarea>
    </label>
 
    <button type="submit"><?php echo $editRow ? 'Update' : 'Add'; ?> Log</button>
    <?php if ($editRow): ?><a href="eggs.php" class="btn-cancel">Cancel</a><?php endif; ?>
</form>
 
<h2>Egg Production Log</h2>
<table>
    <tr><th>Date</th><th>Coop</th><th>Quantity</th><th>Broken</th><th>Notes</th><th>Actions</th></tr>
    <?php foreach ($logs as $l): ?>
    <tr>
        <td><?php echo htmlspecialchars($l['log_date']); ?></td>
        <td><?php echo htmlspecialchars($l['coop_location']); ?></td>
        <td><?php echo htmlspecialchars($l['quantity']); ?></td>
        <td><?php echo htmlspecialchars($l['broken_count']); ?></td>
        <td><?php echo htmlspecialchars($l['notes']); ?></td>
        <td>
            <a href="eggs.php?edit=<?php echo $l['id']; ?>">Edit</a> |
            <a href="eggs.php?delete=<?php echo $l['id']; ?>" onclick="return confirm('Delete this log?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>
 