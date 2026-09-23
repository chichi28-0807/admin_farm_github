<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - Chicken Farm' : 'Chicken Farm Manager'; ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <h1>🐔 Chicken Farm Manager</h1>
</header>
<nav class="navbar">
    <a href="index.php">Dashboard</a>
    <a href="chickens.php">Flock</a>
    <a href="eggs.php">Egg Production</a>
    <a href="feed.php">Feed Inventory</a>
    <a href="health.php">Health Records</a>
    <a href="sales.php">Sales</a>
</nav>
<main class="content">
 