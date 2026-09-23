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
  <?php render_crud_table('Supplier Directory', 'supplier_directory', crud_rows('supplier_directory', $suppliers), 'inventory_procurement'); ?>
  <?php render_crud_table('Purchase Orders', 'purchase_orders', crud_rows('purchase_orders', $purchaseOrders), 'inventory_procurement'); ?>
  <?php render_crud_table('Inventory Items', 'inventory_items', crud_rows('inventory_items', $inventoryItems), 'inventory_procurement'); ?>
  <?php render_crud_table('Vaccination Stock', 'vaccination_stock', crud_rows('vaccination_stock', $vaccinationStock), 'inventory_procurement'); ?>
  <?php render_crud_table('Stock Transactions', 'stock_transactions', crud_rows('stock_transactions', $stockTransactions), 'inventory_procurement'); ?>
</main>
