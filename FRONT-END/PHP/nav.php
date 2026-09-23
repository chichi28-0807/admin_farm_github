<?php
$userRole = $_SESSION['user_role'] ?? 'admin';
$currentPage = $currentPage ?? 'dashboard';
?>
    <nav class="nav-menu">
      <div class="nav-heading">Operations</div>
      <?php if (in_array($userRole, ['admin', 'manager', 'employee'], true)): ?>
      <ul class="nav-list">
        <li><a href="temple.php?page=dashboard" class="nav-link<?php echo $currentPage === 'dashboard' ? ' active' : ''; ?>">Dashboard</a></li>
        <li><a href="temple.php?page=flocks" class="nav-link<?php echo $currentPage === 'flocks' ? ' active' : ''; ?>">Flocks &amp; Batches</a></li>
        <li><a href="#daily-logs" class="nav-link">Daily Records</a></li>
        <li><a href="#feed" class="nav-link">Feed &amp; Silos</a></li>
      </ul>

      <div class="nav-heading">Health &amp; Hardware</div>
      <ul class="nav-list">
        <li><a href="#vaccines" class="nav-link">Vaccination Schedule</a></li>
        <li><a href="#telemetry" class="nav-link">IoT House Climate</a></li>
      </ul>
      <?php endif; ?>

      <?php if (in_array($userRole, ['admin', 'manager'], true)): ?>
      <div class="nav-heading">Commercial</div>
      <ul class="nav-list">
        <li><a href="#sales" class="nav-link">Sales &amp; Orders</a></li>
        <li><a href="#reports" class="nav-link">Reports &amp; P&amp;L</a></li>
      </ul>
      <?php endif; ?>

      <div class="sidebar-footer">
        <a href="LOGIN.PHP?logout=1" class="nav-link">Sign out</a>
      </div>
    </nav>
