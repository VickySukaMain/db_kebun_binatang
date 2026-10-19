<?php
include "koneksi.php";
wajib_admin();
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_hewan'];
    $gambar = $_POST['gambar_lama'] ?? '';
    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            if (!is_dir("uploads")) mkdir("uploads");
            $nama = uniqid("hewan_") . "." . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $nama)) $gambar = $nama;
        }
    }
    $nm = $_POST['nama_hewan']; $jn = $_POST['jenis']; $sp = $_POST['spesies'];
    $um = (int)$_POST['umur']; $jk = $_POST['jenis_kelamin']; $kd = (int)$_POST['id_kandang'];
    if ($id) jalankan($conn, "UPDATE hewan SET nama_hewan=?,jenis=?,spesies=?,umur=?,jenis_kelamin=?,id_kandang=?,gambar=? WHERE id_hewan=?", "sssisisi", $nm, $jn, $sp, $um, $jk, $kd, $gambar, $id);
    else jalankan($conn, "INSERT INTO hewan (nama_hewan,jenis,spesies,umur,jenis_kelamin,id_kandang,gambar) VALUES (?,?,?,?,?,?,?)", "sssisis", $nm, $jn, $sp, $um, $jk, $kd, $gambar);
    header("Location: hewan.php?ok=1"); exit;
}

$d = ['id_hewan'=>0,'nama_hewan'=>'','jenis'=>'','spesies'=>'','umur'=>'','jenis_kelamin'=>'Jantan','id_kandang'=>'','gambar'=>''];
if ($id) $d = mysqli_fetch_assoc(ambil($conn, "SELECT * FROM hewan WHERE id_hewan=?", "i", $id)) ?: $d;
$kandang = mysqli_query($conn, "SELECT * FROM kandang ORDER BY nomor_kandang");
$judul = $id ? "Edit Hewan" : "Tambah Hewan";
include "header.php";
?>
<form class="card form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_hewan" value="<?= $d['id_hewan'] ?>">
    <input type="hidden" name="gambar_lama" value="<?= e($d['gambar']) ?>">
    <label>Nama Hewan</label><input type="text" name="nama_hewan" value="<?= e($d['nama_hewan']) ?>" required>
    <label>Jenis</label>
    <?= pilihan('jenis', ['Mamalia','Reptil','Burung','Amfibi','Ikan','Primata','Serangga'], $d['jenis'], '-- pilih jenis --') ?>
    <label>Spesies</label><input type="text" name="spesies" value="<?= e($d['spesies']) ?>" required>
    <label>Umur (tahun)</label><input type="number" name="umur" min="0" value="<?= e($d['umur']) ?>" required>
    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin">
        <?php foreach (['Jantan','Betina'] as $j) echo "<option " . ($d['jenis_kelamin'] == $j ? 'selected' : '') . ">$j</option>"; ?>
    </select>
    <label>Kandang</label>
    <select name="id_kandang" required>
        <option value="">-- pilih kandang --</option>
        <?php while ($k = mysqli_fetch_assoc($kandang)) echo "<option value='{$k['id_kandang']}' " . ($d['id_kandang'] == $k['id_kandang'] ? 'selected' : '') . ">" . e($k['nomor_kandang']) . " - " . e($k['lokasi']) . "</option>"; ?>
    </select>
    <label>Gambar Hewan (jpg/png/webp)</label>
    <?php if ($d['gambar']) echo "<img class='preview' src='uploads/" . e($d['gambar']) . "'>"; ?>
    <input type="file" name="gambar" accept="image/*">
    <button class="btn" type="submit">Simpan</button> <a class="btn abu" href="hewan.php">Batal</a>
</form>
<?php include "footer.php"; ?>
