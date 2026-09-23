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
  <?php render_crud_table('Feed Inventory', 'feed_inventory', crud_rows('feed_inventory', $feedInventory), 'feed_production'); ?>
  <?php render_crud_table('Feed Formulations', 'feed_formulations', crud_rows('feed_formulations', $feedTypes), 'feed_production'); ?>
  <?php render_crud_table('Daily Feed Usage', 'feed_usage', crud_rows('feed_usage', $feedUsage), 'feed_production'); ?>
</main>
