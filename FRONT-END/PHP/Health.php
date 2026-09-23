<?php
require_once __DIR__ . '/config.php';
$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo instanceof PDO) {
    throw new RuntimeException('Database connection is not configured.');
}
$pageTitle = 'Health Records';
 
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM health_records WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: health.php');
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chickenId = (int)$_POST['chicken_id'];
    $date = $_POST['record_date'];
    $type = $_POST['record_type'];
    $desc = trim($_POST['description']);
    $vet = trim($_POST['vet_name']);
 
    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE health_records SET chicken_id=?, record_date=?, record_type=?, description=?, vet_name=? WHERE id=?");
        $stmt->execute([$chickenId, $date, $type, $desc, $vet, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO health_records (chicken_id, record_date, record_type, description, vet_name) VALUES (?,?,?,?,?)");
        $stmt->execute([$chickenId, $date, $type, $desc, $vet]);
    }
    header('Location: health.php');
    exit;
}
 
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM health_records WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editRow = $stmt->fetch();
}
 
$chickens = $pdo->query("SELECT id, tag_number, breed FROM chickens ORDER BY tag_number")->fetchAll();
 
$records = $pdo->query("
    SELECT h.*, c.tag_number, c.breed
    FROM health_records h
    JOIN chickens c ON c.id = h.chicken_id
    ORDER BY h.record_date DESC
")->fetchAll();
 
include 'header.php';
?>
 
<h2><?php echo $editRow ? 'Edit Health Record' : 'Add Health Record'; ?></h2>
<form method="POST" class="form-grid">
    <input type="hidden" name="id" value="<?php echo $editRow['id'] ?? ''; ?>">
 
    <label>Chicken
        <select name="chicken_id" required>
            <option value="">Select chicken</option>
            <?php foreach ($chickens as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo (($editRow['chicken_id'] ?? '') == $c['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($c['tag_number'] . ' - ' . $c['breed']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
 
    <label>Date
        <input type="date" name="record_date" required value="<?php echo $editRow['record_date'] ?? date('Y-m-d'); ?>">
    </label>
 
    <label>Record Type
        <select name="record_type">
            <?php foreach (['Vaccination','Treatment','Checkup','Illness'] as $t): ?>
                <option value="<?php echo $t; ?>" <?php echo (($editRow['record_type'] ?? '') === $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
 
    <label>Vet Name
        <input type="text" name="vet_name" value="<?php echo htmlspecialchars($editRow['vet_name'] ?? ''); ?>">
    </label>
 
    <label class="full-width">Description
        <textarea name="description"><?php echo htmlspecialchars($editRow['description'] ?? ''); ?></textarea>
    </label>
 
    <button type="submit"><?php echo $editRow ? 'Update' : 'Add'; ?> Record</button>
    <?php if ($editRow): ?><a href="health.php" class="btn-cancel">Cancel</a><?php endif; ?>
</form>
 
<h2>Health Records</h2>
<table>
    <tr><th>Date</th><th>Chicken</th><th>Type</th><th>Vet</th><th>Description</th><th>Actions</th></tr>
    <?php foreach ($records as $r): ?>
    <tr>
        <td><?php echo htmlspecialchars($r['record_date']); ?></td>
        <td><?php echo htmlspecialchars($r['tag_number']); ?></td>
        <td><?php echo htmlspecialchars($r['record_type']); ?></td>
        <td><?php echo htmlspecialchars($r['vet_name']); ?></td>
        <td><?php echo htmlspecialchars($r['description']); ?></td>
        <td>
            <a href="health.php?edit=<?php echo $r['id']; ?>">Edit</a> |
            <a href="health.php?delete=<?php echo $r['id']; ?>" onclick="return confirm('Delete this record?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 
<?php include 'footer.php'; ?>
 