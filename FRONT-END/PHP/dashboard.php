<!-- Dashboard template loaded by Temple.php -->
<?php
require_once __DIR__ . '/module_helpers.php';
$dashboardMetrics = crud_rows('dashboard_metrics', [
  ['metric_id' => 'KPI-001', 'name' => 'Live Population', 'value' => '1000', 'unit' => 'birds', 'status' => 'healthy'],
  ['metric_id' => 'KPI-002', 'name' => 'Mortality Today', 'value' => '8', 'unit' => 'birds', 'status' => 'within target'],
  ['metric_id' => 'KPI-003', 'name' => 'Feed Conversion', 'value' => '1.44', 'unit' => 'ratio', 'status' => 'efficient'],
  ['metric_id' => 'KPI-004', 'name' => 'Average Weight', 'value' => '1820', 'unit' => 'grams', 'status' => 'above target'],
  ['metric_id' => 'KPI-005', 'name' => 'Water Intake', 'value' => '6850', 'unit' => 'liters', 'status' => 'normal'],
]);
?>
<header class="top-header">
  <div class="header-right">
    <button id="openLogModalBtn" class="btn btn-primary">+ Log Daily Event</button>
    <button id="notificationBtn" class="btn-icon" aria-label="View notifications">
      <span class="notification-dot"></span>
      Alerts
    </button>
  </div>
</header>

<main class="content-area">
  <section class="page-header">
    <div>
      <h1 class="page-title">Operational KPI Performance</h1>
      <p class="page-subtitle">Performance Data</p>
    </div>
    <div class="cycle-pill">Cycle Day: <strong>3 Months</strong></div>
  </section>

  <section class="module-mini-grid">
    <article class="mini-module-card module-blue">
      <span class="mini-module-label">Livestock Management</span>
      <strong class="mini-module-value">680 Heads</strong>
    </article>
    <article class="mini-module-card module-green">
      <span class="mini-module-label">Feed Production</span>
      <strong class="mini-module-value">4,200 kg</strong>
    </article>
    <article class="mini-module-card module-yellow">
      <span class="mini-module-label">Equipment Management</span>
      <strong class="mini-module-value">18 Active</strong>
    </article>
    <article class="mini-module-card module-purple">
      <span class="mini-module-label">Inventory &amp; Procurement</span>
      <strong class="mini-module-value">96% Stock</strong>
    </article>
    <article class="mini-module-card module-red">
      <span class="mini-module-label">Sales Management</span>
      <strong class="mini-module-value">$14.8K</strong>
    </article>
  </section>

  <section class="kpi-grid">
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Live Population</h2></header>
      <div class="kpi-value">1000</div>
      <footer class="kpi-footer"><span>Placed: 1000</span><span class="pill pill-success">97.5% Livability</span></footer>
    </article>
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Mortality (Today)</h2></header>
      <div class="kpi-value">8 <small>birds</small></div>
      <footer class="kpi-footer"><span>Cumul: 2.48%</span><span class="pill pill-success">&lt; 3.0% Target</span></footer>
    </article>
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Feed Conversion (FCR)</h2></header>
      <div class="kpi-value">1.44</div>
      <footer class="kpi-footer"><span>Target: 1.48</span><span class="pill pill-success">-0.04 Efficient</span></footer>
    </article>
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Average Weight</h2></header>
      <div class="kpi-value">1,820 <small>g</small></div>
      <footer class="kpi-footer"><span>Breed Ref: 1,770g</span><span class="pill pill-info">+50g vs Target</span></footer>
    </article>
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Feed Consumed Today</h2></header>
      <div class="kpi-value">3,725 <small>kg</small></div>
      <footer class="kpi-footer"><span>156 g / bird</span><span>Finisher 1</span></footer>
    </article>
    <article class="kpi-card">
      <header class="kpi-header"><h2 class="kpi-label">Water Intake</h2></header>
      <div class="kpi-value">6,850 <small>L</small></div>
      <footer class="kpi-footer"><span>Ratio: 1.84 L/kg</span><span class="pill pill-success">Normal</span></footer>
    </article>
  </section>

  <div class="dashboard-split">
    <section class="card chart-card">
      <header class="card-header">
        <div>
          <h2 class="card-title">Flock Weight Gain vs Breed Standard (g)</h2>
          <p class="card-subtitle">Sampled weight compared against Ross 308 performance curve</p>
        </div>
        <div class="chart-legend"><span class="legend-item legend-actual">Actual (g)</span><span class="legend-item legend-benchmark">Benchmark (g)</span></div>
      </header>
      <div class="chart-container"><canvas id="growthChart"></canvas></div>
    </section>

    <section class="card telemetry-card">
      <header class="card-header">
        <div><h2 class="card-title">IoT Shed Climate Telemetry</h2><p class="card-subtitle">Real-time environmental sensors</p></div>
        <span class="pill pill-success">Live Sync</span>
      </header>
      <div class="sensor-matrix">
        <article class="sensor-box">
          <div class="sensor-box-header"><span class="sensor-title">House 01</span><span class="tag tag-normal">Normal</span></div>
          <ul class="sensor-readings"><li><span>Temp:</span> <strong>22.8 °C</strong></li><li><span>Humidity:</span> <strong>63%</strong></li><li><span>Ammonia:</span> <strong>11 ppm</strong></li><li><span>Ventilation:</span> <strong>75%</strong></li></ul>
        </article>
        <article class="sensor-box sensor-box-alert">
          <div class="sensor-box-header"><span class="sensor-title">House 02</span><span class="tag tag-warning">Warning</span></div>
          <ul class="sensor-readings"><li><span>Temp:</span> <strong>26.4 °C</strong></li><li><span>Humidity:</span> <strong>68%</strong></li><li><span>Ammonia:</span> <strong>14 ppm</strong></li><li><span>Ventilation:</span> <strong>100% Boost</strong></li></ul>
        </article>
      </div>
      <div class="silo-container">
        <div class="silo-labels"><span>Silo 1 (Finisher Pellets)</span><strong>14.2 / 20 Tons</strong></div>
        <div class="progress-bar"><div class="progress-fill" style="width: 71%;"></div></div>
        <span class="silo-footnote">Estimated 3.8 days of supply remaining</span>
      </div>
    </section>
  </div>

  <?php render_crud_table('Dashboard KPI Records', 'dashboard_metrics', $dashboardMetrics, 'dashboard'); ?>
</main>
