<?php

require_once __DIR__ . '/module_helpers.php';
$dataRoot = dirname(__DIR__, 2);
$customers = require $dataRoot . '/sales_management/customer.php';
$sales = require $dataRoot . '/sales_management/sale.php';
$saleItems = require $dataRoot . '/sales_management/sale_item.php';

if (!function_exists('render_data_table')) {
  function render_data_table(string $title, array $rows): void
  {
    if ($rows === []) {
      return;
    }

    $columns = array_keys($rows[0]);
    echo '<section class="card module-data-section">';
    echo '<header class="card-header"><h2 class="card-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2><span class="pill pill-info">' . count($rows) . ' records</span></header>';
    echo '<div class="table-container"><table class="data-table"><thead><tr>';
    foreach ($columns as $column) {
      echo '<th>' . htmlspecialchars(ucwords(str_replace('_', ' ', $column)), ENT_QUOTES, 'UTF-8') . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($rows as $row) {
      echo '<tr>';
      foreach ($columns as $column) {
        $value = is_array($row[$column]) ? json_encode($row[$column], JSON_UNESCAPED_SLASHES) : $row[$column];
        echo '<td>' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</td>';
      }
      echo '</tr>';
    }
    echo '</tbody></table></div></section>';
  }
}
?>
<main class="content-area">
  <section class="page-header">
    <div><h1 class="page-title">Sales Management</h1><p class="page-subtitle">Review customers, sales transactions, and itemized farm products.</p></div>
  </section>
  <?php render_data_table('Customer Directory', $customers); ?>
  <?php render_data_table('Sales Transactions', $sales); ?>
  <?php render_data_table('Sale Items', $saleItems); ?>
</main>
