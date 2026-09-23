<?php
$userRole = $_SESSION['user_role'] ?? 'admin';
$currentPage = $currentPage ?? 'dashboard';
?>
    <nav class="nav-menu">
      <div class="overview-module">
        <div class="overview-module-label">Dashboard Module</div>
        <div class="overview-module-row">
          <span>Livestock Management</span>
          <strong>680 Heads</strong>
        </div>
        <div class="overview-module-row">
          <span>Feed Production</span>
          <strong>4,200 kg</strong>
        </div>
        <div class="overview-module-row">
          <span>Equipment Management</span>
          <strong>18 Active</strong>
        </div>
        <div class="overview-module-row">
          <span>Inventory &amp; Procurement</span>
          <strong>96% Stock</strong>
        </div>
        <div class="overview-module-row">
          <span>Sales Management</span>
          <strong>$14.8K</strong>
        </div>
      </div>

      <div class="nav-heading">Operations</div>
      <?php if (in_array($userRole, ['admin', 'manager', 'employee'], true)): ?>
      <ul class="nav-list">
        <li><a href="Temple.php?page=dashboard" class="nav-link<?php echo $currentPage === 'dashboard' ? ' active' : ''; ?>">Livestock Management</a></li>
        <li><a href="#" class="nav-link">Feed Production</a></li>
        <li><a href="#" class="nav-link">Equipment Management</a></li>
        <li><a href="#" class="nav-link">Inventory &amp; Procurement</a></li>
        <li><a href="#" class="nav-link">Sales Management</a></li>
      </ul>
      <?php endif; ?>

      <div class="sidebar-footer">
        <a href="LOGIN.PHP?logout=1" class="nav-link">Sign out</a>
      </div>
    </nav>
