<?php
session_start();

if (empty($_SESSION['authenticated'])) {
    header('Location: LOGIN.PHP');
    exit;
}

$currentPage = preg_replace('/[^a-z0-9_-]/i', '', $_GET['page'] ?? 'dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chicken Farm Management</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="main-wrapper">
        <aside class="sidebar">
            <h2 class="logo">Farm</h2>
            <?php require_once('nav.php'); ?>
        </aside>

        <main class="dashboard-page">
            <?php
            $dashboardFile = __DIR__ . '/dashboard.html';
            $pageFile = __DIR__ . '/' . ($currentPage ?: 'dashboard') . '.html';
            $dashboardMarkup = is_file($dashboardFile) ? file_get_contents($dashboardFile) : false;
            $pageMarkup = is_file($pageFile) ? file_get_contents($pageFile) : false;
            $headerMarkup = '';
            $contentMarkup = '<p class="error">Page not found.</p>';

            if ($dashboardMarkup !== false) {
                if (preg_match('/<header class="top-header">.*?<\/header>/s', $dashboardMarkup, $headerMatch)) {
                    $headerMarkup = $headerMatch[0];
                }
            }
            if ($pageMarkup !== false && preg_match('/<main class="content-area">(.*?)<\/main>/s', $pageMarkup, $contentMatch)) {
                $contentMarkup = $contentMatch[1];
            }
            echo $headerMarkup;
            ?>
            <div class="content-area">
                <?php echo $contentMarkup; ?>
            </div>
        </main>
    </div>
</body>
</html>