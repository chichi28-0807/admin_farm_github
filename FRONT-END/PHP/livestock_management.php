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
  <?php render_crud_table('Livestock Profiles', 'livestock_profiles', crud_rows('livestock_profiles', $livestock), 'livestock_management'); ?>
  <?php render_crud_table('Holding Pens', 'holding_pens', crud_rows('holding_pens', $pens), 'livestock_management'); ?>
  <?php render_crud_table('Health Records', 'health_records', crud_rows('health_records', $healthRecords), 'livestock_management'); ?>
  <?php render_crud_table('Carcass Processing', 'carcass_processing', crud_rows('carcass_processing', $carcasses), 'livestock_management'); ?>
</main>
