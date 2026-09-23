<?php

require_once __DIR__ . '/module_helpers.php';
$dataRoot = dirname(__DIR__, 2);
$suppliers = require $dataRoot . '/inventory_procurement/supplier.php';
$purchaseOrders = require $dataRoot . '/inventory_procurement/purchase_order.php';
$inventoryItems = require $dataRoot . '/inventory_procurement/inventory_item.php';
$vaccinationStock = require $dataRoot . '/inventory_procurement/vaccination_stock.php';
$stockTransactions = require $dataRoot . '/inventory_procurement/stock_transaction.php';
?>
<main class="content-area">
  <section class="page-header">
    <div><h1 class="page-title">Inventory &amp; Procurement</h1><p class="page-subtitle">Manage vendors, purchase orders, supplies, vaccines, and stock movement.</p></div>
  </section>
  <?php render_data_table('Supplier Directory', $suppliers); ?>
  <?php render_data_table('Purchase Orders', $purchaseOrders); ?>
  <?php render_data_table('Inventory Items', $inventoryItems); ?>
  <?php render_data_table('Vaccination Stock', $vaccinationStock); ?>
  <?php render_data_table('Stock Transactions', $stockTransactions); ?>
</main>
