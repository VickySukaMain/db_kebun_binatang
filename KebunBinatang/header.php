<?php
wajib_login();
$hal = basename($_SERVER['PHP_SELF']);
$menu = ['dashboard.php'=>'🏠 Dashboard','hewan.php'=>'🐘 Hewan','kandang.php'=>'🏠 Kandang','pakan.php'=>'🍖 Pakan','perawatan.php'=>'💉 Perawatan','petugas.php'=>'👷 Petugas'];
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($judul) ?> - Kebun Binatang</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav class="nav">
    <div class="brand">🦁 Kebun Binatang</div>
    <div class="menu">
    <?php foreach ($menu as $f => $n) {
        $aktif = (strpos($hal, rtrim($f, '.php')) === 0) ? 'aktif' : '';
        echo "<a class='$aktif' href='$f'>$n</a>";
    } ?>
    </div>
    <div class="user">👤 <?= e($_SESSION['user']) ?> (<?= e($_SESSION['role']) ?>) <a href="logout.php">Keluar</a></div>
</nav>
<main class="container">
<h1><?= e($judul) ?></h1>
