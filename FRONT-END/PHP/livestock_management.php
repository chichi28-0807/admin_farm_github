<?php

require_once __DIR__ . '/module_helpers.php';
$dataRoot = dirname(__DIR__, 2);
$livestock = require $dataRoot . '/livestock_management/livestock.php';
$pens = require $dataRoot . '/livestock_management/pen.php';
$healthRecords = require $dataRoot . '/livestock_management/health_record.php';
$carcasses = require $dataRoot . '/livestock_management/carcass.php';
?>
<main class="content-area">
  <section class="page-header">
    <div><h1 class="page-title">Livestock Management</h1><p class="page-subtitle">Profiles, housing, health activity, and processing records.</p></div>
  </section>
  <?php render_data_table('Livestock Profiles', $livestock); ?>
  <?php render_data_table('Holding Pens', $pens); ?>
  <?php render_data_table('Health Records', $healthRecords); ?>
  <?php render_data_table('Carcass Processing', $carcasses); ?>
</main>
