<?php

require_once __DIR__ . '/module_helpers.php';
$dataRoot = dirname(__DIR__, 2);
$feedInventory = require $dataRoot . '/feed_production/feed_inventory.php';
$feedTypes = require $dataRoot . '/feed_production/feed_type.php';
$feedUsage = require $dataRoot . '/feed_production/feed_usage.php';
?>
<main class="content-area">
  <section class="page-header">
    <div><h1 class="page-title">Feed Production</h1><p class="page-subtitle">Track feed stock, formulations, and daily consumption.</p></div>
  </section>
  <?php render_data_table('Feed Inventory', $feedInventory); ?>
  <?php render_data_table('Feed Formulations', $feedTypes); ?>
  <?php render_data_table('Daily Feed Usage', $feedUsage); ?>
</main>
