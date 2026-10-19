<?php
include "koneksi.php";
wajib_login();
$pesan = $_GET['pesan'] ?? '';
if (isset($_GET['hapus'])) {
    wajib_admin();
    $ok = jalankan($conn, "DELETE FROM kandang WHERE id_kandang=?", "i", (int)$_GET['hapus']);
    header("Location: kandang.php?pesan=" . urlencode($ok ? "Data dihapus." : "Gagal hapus: kandang masih dipakai hewan.")); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wajib_admin();
    $id = (int)$_POST['id']; $lok = $_POST['lokasi']; $kap = (int)$_POST['kapasitas'];
    if ($id) jalankan($conn, "UPDATE kandang SET lokasi=?,kapasitas=? WHERE id_kandang=?", "sii", $lok, $kap, $id);
    else {
        $next = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(MAX(id_kandang),0)+1 FROM kandang"))[0];
        $no = sprintf("K-%02d", $next);
        jalankan($conn, "INSERT INTO kandang (nomor_kandang,lokasi,kapasitas) VALUES (?,?,?)", "ssi", $no, $lok, $kap);
    }
    header("Location: kandang.php?pesan=Data+tersimpan."); exit;
}
$e = ['id_kandang'=>0,'nomor_kandang'=>'','lokasi'=>'','kapasitas'=>''];
if (isset($_GET['edit'])) $e = mysqli_fetch_assoc(ambil($conn, "SELECT * FROM kandang WHERE id_kandang=?", "i", (int)$_GET['edit'])) ?: $e;
$judul = "Data Kandang";
include "header.php";
if ($pesan) echo "<div class='alert ok'>" . e($pesan) . "</div>";
if (is_admin()) { ?>
<form class="card form inline" method="post">
    <input type="hidden" name="id" value="<?= $e['id_kandang'] ?>">
    <input type="text" name="nomor_kandang" placeholder="Nomor kandang" value="<?= e($e['nomor_kandang']) ?>" required>
    <?= pilihan('lokasi', ['Zona Mamalia','Zona Karnivora','Zona Herbivora','Zona Primata','Zona Reptil','Zona Burung','Zona Akuarium'], $e['lokasi'], '-- lokasi --') ?>
    <?= pilihan('kapasitas', [1,2,3,5,10,15,20,25,30,50], $e['kapasitas'], '-- kapasitas --') ?>
    <button class="btn"><?= $e['id_kandang'] ? 'Update' : 'Tambah' ?></button>
    <?php if ($e['id_kandang']) echo '<a class="btn abu" href="kandang.php">Batal</a>'; ?>
</form>
<?php } ?>
<div class="card"><table>
<tr><th>No</th><th>Nomor Kandang</th><th>Lokasi</th><th>Kapasitas</th><?= is_admin() ? '<th>Aksi</th>' : '' ?></tr>
<?php $no = 1; $r = mysqli_query($conn, "SELECT * FROM kandang ORDER BY nomor_kandang");
while ($k = mysqli_fetch_assoc($r)) { ?>
<tr><td><?= $no++ ?></td><td><?= e($k['nomor_kandang']) ?></td><td><?= e($k['lokasi']) ?></td><td><?= e($k['kapasitas']) ?></td>
<?php if (is_admin()) { ?><td><a class="btn sm kuning" href="?edit=<?= $k['id_kandang'] ?>">Edit</a> <a class="btn sm merah" href="?hapus=<?= $k['id_kandang'] ?>" onclick="return confirm('Hapus kandang ini?')">Hapus</a></td><?php } ?></tr>
<?php } ?>
</table></div>
<?php include "footer.php"; ?>
