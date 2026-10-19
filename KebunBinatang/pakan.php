<?php
include "koneksi.php";
wajib_login();
$pesan = $_GET['pesan'] ?? '';
if (isset($_GET['hapus'])) {
    wajib_admin();
    jalankan($conn, "DELETE FROM pakan WHERE id_pakan=?", "i", (int)$_GET['hapus']);
    header("Location: pakan.php?pesan=Data+dihapus."); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wajib_admin();
    $id = (int)$_POST['id']; $hw = (int)$_POST['id_hewan']; $jp = $_POST['jenis_pakan']; $jd = $_POST['jadwal_pemberian'];
    if ($id) jalankan($conn, "UPDATE pakan SET id_hewan=?,jenis_pakan=?,jadwal_pemberian=? WHERE id_pakan=?", "issi", $hw, $jp, $jd, $id);
    else jalankan($conn, "INSERT INTO pakan (id_hewan,jenis_pakan,jadwal_pemberian) VALUES (?,?,?)", "iss", $hw, $jp, $jd);
    header("Location: pakan.php?pesan=Data+tersimpan."); exit;
}
$e = ['id_pakan'=>0,'id_hewan'=>'','jenis_pakan'=>'','jadwal_pemberian'=>''];
if (isset($_GET['edit'])) $e = mysqli_fetch_assoc(ambil($conn, "SELECT * FROM pakan WHERE id_pakan=?", "i", (int)$_GET['edit'])) ?: $e;
$judul = "Data Pakan";
include "header.php";
if ($pesan) echo "<div class='alert ok'>" . e($pesan) . "</div>";
if (is_admin()) { ?>
<form class="card form inline" method="post">
    <input type="hidden" name="id" value="<?= $e['id_pakan'] ?>">
    <select name="id_hewan" required><option value="">-- hewan --</option>
    <?php $hw = mysqli_query($conn, "SELECT id_hewan,nama_hewan FROM hewan ORDER BY nama_hewan");
    while ($h = mysqli_fetch_assoc($hw)) echo "<option value='{$h['id_hewan']}' " . ($e['id_hewan'] == $h['id_hewan'] ? 'selected' : '') . ">" . e($h['nama_hewan']) . "</option>"; ?>
    </select>
    <?= pilihan('jenis_pakan', ['Daging','Ikan','Rumput','Daun','Buah','Sayuran','Biji-bijian','Serangga','Tikus','Pelet','Campuran'], $e['jenis_pakan'], '-- jenis pakan --') ?>
    <input type="time" name="jadwal_pemberian" value="<?= e($e['jadwal_pemberian']) ?>" required>
    <button class="btn"><?= $e['id_pakan'] ? 'Update' : 'Tambah' ?></button>
    <?php if ($e['id_pakan']) echo '<a class="btn abu" href="pakan.php">Batal</a>'; ?>
</form>
<?php } ?>
<div class="card"><table>
<tr><th>No</th><th>Hewan</th><th>Jenis Pakan</th><th>Jam</th><?= is_admin() ? '<th>Aksi</th>' : '' ?></tr>
<?php $no = 1; $r = mysqli_query($conn, "SELECT p.*, h.nama_hewan FROM pakan p LEFT JOIN hewan h ON p.id_hewan=h.id_hewan ORDER BY p.jadwal_pemberian");
while ($p = mysqli_fetch_assoc($r)) { ?>
<tr><td><?= $no++ ?></td><td><?= e($p['nama_hewan']) ?></td><td><?= e($p['jenis_pakan']) ?></td><td><?= e(substr($p['jadwal_pemberian'], 0, 5)) ?></td>
<?php if (is_admin()) { ?><td><a class="btn sm kuning" href="?edit=<?= $p['id_pakan'] ?>">Edit</a> <a class="btn sm merah" href="?hapus=<?= $p['id_pakan'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a></td><?php } ?></tr>
<?php } ?>
</table></div>
<?php include "footer.php"; ?>
