<?php
require 'config.php';
if (!isset($pdo) || !($pdo instanceof PDO)) {
    throw new RuntimeException('Database connection is not configured.');
}
$pageTitle = 'Flock';
 
// Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM chickens WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: chickens.php');
    exit;
}
 
// Add or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tag = trim($_POST['tag_number']);
    $breed = trim($_POST['breed']);
    $gender = $_POST['gender'];
    $hatched = $_POST['date_hatched'] ?: null;
    $coop = trim($_POST['coop_location']);
    $status = $_POST['status'];
    $notes = trim($_POST['notes']);
 
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE chickens SET tag_number=?, breed=?, gender=?, date_hatched=?, coop_location=?, status=?, notes=? WHERE id=?");
        $stmt->execute([$tag, $breed, $gender, $hatched, $coop, $status, $notes, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO chickens (tag_number, breed, gender, date_hatched, coop_location, status, notes) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$tag, $breed, $gender, $hatched, $coop, $status, $notes]);
    }
    header('Location: chickens.php');
    exit;
}
 
// Edit lookup
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM chickens WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRow = $stmt->fetch();
}
 
$chickens = $pdo->query("SELECT * FROM chickens ORDER BY id DESC")->fetchAll();
 
include 'header.php';
?>
 
<h2><?php echo $editRow ? 'Edit Chicken' : 'Add Chicken'; ?></h2>
<form method="POST" class="form-grid">
    <input type="hidden" name="id" value="<?php echo $editRow['id'] ?? ''; ?>">
 
    <label>Tag Number
        <input type="text" name="tag_number" required value="<?php echo htmlspecialchars($editRow['tag_number'] ?? ''); ?>">
    </label>
 
    <label>Breed
        <input type="text" name="breed" required value="<?php echo htmlspecialchars($editRow['breed'] ?? ''); ?>">
    </label>
 
    <label>Gender
        <select name="gender">
            <?php foreach (['Hen','Rooster','Chick'] as $g): ?>
                <option value="<?php echo $g; ?>" <?php echo (($editRow['gender'] ?? '') === $g) ? 'selected' : ''; ?>><?php echo $g; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
 
    <label>Date Hatched
        <input type="date" name="date_hatched" value="<?php echo $editRow['date_hatched'] ?? ''; ?>">
    </label>
 
    <label>Coop Location
        <input type="text" name="coop_location" value="<?php echo htmlspecialchars($editRow['coop_location'] ?? ''); ?>">
    </label>
 
    <label>Status
        <select name="status">
            <?php foreach (['Active','Sold','Deceased'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo (($editRow['status'] ?? '') === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
 
    <label class="full-width">Notes
        <textarea name="notes"><?php echo htmlspecialchars($editRow['notes'] ?? ''); ?></textarea>
    </label>
 
    <button type="submit"><?php echo $editRow ? 'Update' : 'Add'; ?> Chicken</button>
    <?php if ($editRow): ?><a href="chickens.php" class="btn-cancel">Cancel</a><?php endif; ?>
</form>
 
<h2>Flock List (<?php echo count($chickens); ?>)</h2>
<table>
    <tr>
        <th>Tag</th><th>Breed</th><th>Gender</th><th>Hatched</th><th>Coop</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($chickens as $c): ?>
    <tr>
        <td><?php echo htmlspecialchars($c['tag_number']); ?></td>
        <td><?php echo htmlspecialchars($c['breed']); ?></td>
        <td><?php echo htmlspecialchars($c['gender']); ?></td>
        <td><?php echo htmlspecialchars($c['date_hatched']); ?></td>
        <td><?php echo htmlspecialchars($c['coop_location']); ?></td>
        <td><span class="badge badge-<?php echo strtolower($c['status']); ?>"><?php echo $c['status']; ?></span></td>
        <td>
            <a href="chickens.php?edit=<?php echo $c['id']; ?>">Edit</a> |
            <a href="chickens.php?delete=<?php echo $c['id']; ?>" onclick="return confirm('Delete this chicken?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>