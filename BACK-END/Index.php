<?php
require __DIR__ . '/Db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
 
// Allowed tables, columns, and required fields
$resources = [
    'flocks' => [
        'columns'  => ['name', 'breed', 'type', 'quantity', 'date_acquired', 'status'],
        'required' => ['name', 'breed', 'type', 'quantity', 'date_acquired'],
    ],
    'eggs' => [
        'table'    => 'egg_production',
        'columns'  => ['flock_id', 'collect_date', 'eggs_collected', 'eggs_broken'],
        'required' => ['flock_id', 'collect_date', 'eggs_collected'],
    ],
    'feed' => [
        'table'    => 'feed_records',
        'columns'  => ['flock_id', 'feed_type', 'quantity_kg', 'cost', 'feed_date'],
        'required' => ['flock_id', 'feed_type', 'quantity_kg', 'cost', 'feed_date'],
    ],
    'health' => [
        'table'    => 'health_records',
        'columns'  => ['flock_id', 'record_type', 'description', 'mortality_count', 'record_date'],
        'required' => ['flock_id', 'record_type', 'record_date'],
    ],
    'sales' => [
        'columns'  => ['flock_id', 'product', 'quantity', 'unit_price', 'buyer', 'sale_date'],
        'required' => ['product', 'quantity', 'unit_price', 'sale_date'],
    ],
];
 
function respond($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}
 
$method   = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? '';
$id       = isset($_GET['id']) ? (int)$_GET['id'] : null;
$body     = json_decode(file_get_contents('php://input'), true) ?? [];
 
try {
    $pdo = db();
 
    // Dashboard summary: index.php?resource=summary
    if ($resource === 'summary') {
        respond([
            'total_birds'     => (int)$pdo->query("SELECT COALESCE(SUM(quantity),0) FROM flocks WHERE status='active'")->fetchColumn(),
            'active_flocks'   => (int)$pdo->query("SELECT COUNT(*) FROM flocks WHERE status='active'")->fetchColumn(),
            'eggs_today'      => (int)$pdo->query("SELECT COALESCE(SUM(eggs_collected - eggs_broken),0) FROM egg_production WHERE collect_date = CURDATE()")->fetchColumn(),
            'total_sales'     => (float)$pdo->query("SELECT COALESCE(SUM(quantity * unit_price),0) FROM sales")->fetchColumn(),
            'total_feed_cost' => (float)$pdo->query("SELECT COALESCE(SUM(cost),0) FROM feed_records")->fetchColumn(),
        ]);
    }
 
    if (!isset($resources[$resource])) {
        respond(['error' => 'Unknown resource. Use: flocks, eggs, feed, health, sales, summary'], 404);
    }
 
    $cfg     = $resources[$resource];
    $table   = $cfg['table'] ?? $resource;
    $columns = $cfg['columns'];
 
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                $row ? respond($row) : respond(['error' => 'Not found'], 404);
            }
            $sql  = "SELECT * FROM `$table`";
            $args = [];
            if (isset($_GET['flock_id']) && in_array('flock_id', $columns, true)) {
                $sql .= " WHERE flock_id = ?";
                $args[] = (int)$_GET['flock_id'];
            }
            $stmt = $pdo->prepare($sql . " ORDER BY id DESC");
            $stmt->execute($args);
            respond($stmt->fetchAll());
            break;

        case 'POST':
            foreach ($cfg['required'] as $field) {
                if (!isset($body[$field]) || $body[$field] === '') {
                    respond(['error' => "Missing field: $field"], 422);
                }
            }
            $data = array_intersect_key($body, array_flip($columns));
            $pdo->beginTransaction();
            $names = implode(',', array_map(fn($c) => "`$c`", array_keys($data)));
            $marks = implode(',', array_fill(0, count($data), '?'));
            $stmt  = $pdo->prepare("INSERT INTO `$table` ($names) VALUES ($marks)");
            $stmt->execute(array_values($data));
            $newId = (int)$pdo->lastInsertId();

            // Deaths reduce the flock's bird count
            if ($resource === 'health' && !empty($data['mortality_count'])) {
                $pdo->prepare("UPDATE flocks SET quantity = GREATEST(quantity - ?, 0) WHERE id = ?")
                    ->execute([(int)$data['mortality_count'], (int)$data['flock_id']]);
            }
            $pdo->commit();
            respond(['id' => $newId, 'message' => 'Created'], 201);
            break;

        case 'PUT':
            if (!$id) respond(['error' => 'id is required'], 400);
            $data = array_intersect_key($body, array_flip($columns));
            if (!$data) respond(['error' => 'No valid fields to update'], 422);
            $set  = implode(',', array_map(fn($c) => "`$c` = ?", array_keys($data)));
            $stmt = $pdo->prepare("UPDATE `$table` SET $set WHERE id = ?");
            $stmt->execute([...array_values($data), $id]);
            respond(['message' => 'Updated']);
            break;

        case 'DELETE':
            if (!$id) respond(['error' => 'id is required'], 400);
            $pdo->prepare("DELETE FROM `$table` WHERE id = ?")->execute([$id]);
            respond(['message' => 'Deleted']);
            break;

        default:
            respond(['error' => 'Method not allowed'], 405);
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    respond(['error' => 'Server error', 'detail' => $e->getMessage()], 500);
}