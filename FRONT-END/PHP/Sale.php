<?php
require 'config.php';
$pdo = $pdo ?? null;
if (!$pdo instanceof PDO) {
    throw new RuntimeException('Database connection is not available.');
}
$pageTitle = 'Sales';
 
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: sales.php');
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item_type'];
    $qty = (int)$_POST['quantity'];
    $price = (float)$_POST['unit_price'];
    $total = $qty * $price;
    $buyer = trim($_POST['buyer_name']);
    $date = $_POST['sale_date'];
 
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE sales SET item_type=?, quantity=?, unit_price=?, total_amount=?, buyer_name=?, sale_date=? WHERE id=?");
        $stmt->execute([$item, $qty, $price, $total, $buyer, $date, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO sales (item_type, quantity, unit_price, total_amount, buyer_name, sale_date) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$item, $qty, $price, $total, $buyer, $date]);
    }
    header('Location: sales.php');
    exit;
}
 
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRow = $stmt->fetch();
}
 
$sales = $pdo->query("SELECT * FROM sales ORDER BY sale_date DESC")->fetchAll();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM sales")->fetchColumn();
 
include 'header.php';
?>
 
<h2><?php echo $editRow ? 'Edit Sale' : 'Record Sale'; ?></h2>
<form method="POST" class="form-grid">
    <input type="hidden" name="id" value="<?php echo $editRow['id'] ?? ''; ?>">
 
    <label>Item
        <select name="item_type">
            <?php foreach (['Eggs','Chicken'] as $i): ?>
                <option value="<?php echo $i; ?>" <?php echo (($editRow['item_type'] ?? '') === $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
 
    <label>Quantity
        <input type="number" name="quantity" min="1" required value="<?php echo $editRow['quantity'] ?? 1; ?>">
    </label>
 
    <label>Unit Price ($)
        <input type="number" step="0.01" name="unit_price" required value="<?php echo $editRow['unit_price'] ?? ''; ?>">
    </label>
 
    <label>Buyer Name
        <input type="text" name="buyer_name" value="<?php echo htmlspecialchars($editRow['buyer_name'] ?? ''); ?>">
    </label>
 
    <label>Sale Date
        <input type="date" name="sale_date" required value="<?php echo $editRow['sale_date'] ?? date('Y-m-d'); ?>">
    </label>
 
    <button type="submit"><?php echo $editRow ? 'Update' : 'Add'; ?> Sale</button>
    <?php if ($editRow): ?><a href="sales.php" class="btn-cancel">Cancel</a><?php endif; ?>
</form>
 
<h2>Sales History &mdash; Total Revenue: $<?php echo number_format($totalRevenue, 2); ?></h2>
<table>
    <tr><th>Date</th><th>Item</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Buyer</th><th>Actions</th></tr>
    <?php foreach ($sales as $s): ?>
    <tr>
        <td><?php echo htmlspecialchars($s['sale_date']); ?></td>
        <td><?php echo htmlspecialchars($s['item_type']); ?></td>
        <td><?php echo htmlspecialchars($s['quantity']); ?></td>
        <td>$<?php echo number_format($s['unit_price'], 2); ?></td>
        <td>$<?php echo number_format($s['total_amount'], 2); ?></td>
        <td><?php echo htmlspecialchars($s['buyer_name']); ?></td>
        <td>
            <a href="sales.php?edit=<?php echo $s['id']; ?>">Edit</a> |
            <a href="sales.php?delete=<?php echo $s['id']; ?>" onclick="return confirm('Delete this sale?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>
 