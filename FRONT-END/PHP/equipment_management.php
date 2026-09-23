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
  <?php render_crud_table('Equipment Registry', 'equipment_registry', crud_rows('equipment_registry', $equipment), 'equipment_management'); ?>
  <?php render_crud_table('Maintenance Records', 'maintenance_records', crud_rows('maintenance_records', $maintenanceRecords), 'equipment_management'); ?>
  <?php render_crud_table('Equipment Usage', 'equipment_usage', crud_rows('equipment_usage', $equipmentUsage), 'equipment_management'); ?>
  <?php render_crud_table('Employee Directory', 'employee_directory', crud_rows('employee_directory', $employees), 'equipment_management'); ?>
</main>
