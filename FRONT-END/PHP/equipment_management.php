<?php

require_once __DIR__ . '/module_helpers.php';
$dataRoot = dirname(__DIR__, 2);
$equipment = require $dataRoot . '/equipment_management/equipment.php';
$maintenanceRecords = require $dataRoot . '/equipment_management/maintenance_record.php';
$equipmentUsage = require $dataRoot . '/equipment_management/equipment_usage.php';
$employees = require $dataRoot . '/equipment_management/employee.php';
?>
<main class="content-area">
  <section class="page-header">
    <div><h1 class="page-title">Equipment Management</h1><p class="page-subtitle">Monitor farm assets, service history, activity, and operators.</p></div>
  </section>
  <?php render_data_table('Equipment Registry', $equipment); ?>
  <?php render_data_table('Maintenance Records', $maintenanceRecords); ?>
  <?php render_data_table('Equipment Usage', $equipmentUsage); ?>
  <?php render_data_table('Employee Directory', $employees); ?>
</main>
