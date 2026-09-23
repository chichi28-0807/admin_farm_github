<?php
$userRole = $_SESSION['user_role'] ?? 'admin';
$currentPage = $currentPage ?? 'dashboard';
?>
    <nav class="nav-menu">
      <div class="nav-heading">Farm</div>
      <ul class="nav-list">
        <li><a href="Temple.php?page=dashboard" class="nav-link<?php echo $currentPage === 'dashboard' ? ' active' : ''; ?>">Dashboard</a></li>
      </ul>

      <div class="nav-heading">Operations</div>
      <?php if (in_array($userRole, ['admin', 'manager', 'employee'], true)): ?>
      <ul class="nav-list">
        <li><a href="Temple.php?page=livestock_management" class="nav-link<?php echo $currentPage === 'livestock_management' ? ' active' : ''; ?>">Livestock Management</a></li>
        <li><a href="Temple.php?page=feed_production" class="nav-link<?php echo $currentPage === 'feed_production' ? ' active' : ''; ?>">Feed Production</a></li>
        <li><a href="Temple.php?page=equipment_management" class="nav-link<?php echo $currentPage === 'equipment_management' ? ' active' : ''; ?>">Equipment Management</a></li>
        <li><a href="Temple.php?page=inventory_procurement" class="nav-link<?php echo $currentPage === 'inventory_procurement' ? ' active' : ''; ?>">Inventory &amp; Procurement</a></li>
        <li><a href="Temple.php?page=sales_management" class="nav-link<?php echo $currentPage === 'sales_management' ? ' active' : ''; ?>">Sales Management</a></li>
      </ul>
      <?php endif; ?>

      <div class="sidebar-footer">
        <a href="LOGIN.PHP?logout=1" class="nav-link">Sign out</a>
      </div>
    </nav>
