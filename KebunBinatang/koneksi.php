<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect("localhost", "root", "", "db_kebun_binatang");
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES); }
function wajib_login() { if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; } }
function is_admin() { return ($_SESSION['role'] ?? '') === 'admin'; }
function wajib_admin() { wajib_login(); if (!is_admin()) die("Akses ditolak. Hanya admin yang boleh."); }

// SELECT -> hasil query
function ambil($conn, $sql, $types = "", ...$p) {
    $st = mysqli_prepare($conn, $sql);
    if ($types) mysqli_stmt_bind_param($st, $types, ...$p);
    mysqli_stmt_execute($st);
    return mysqli_stmt_get_result($st);
}
// INSERT / UPDATE / DELETE -> true/false
function jalankan($conn, $sql, $types = "", ...$p) {
    $st = mysqli_prepare($conn, $sql);
    if ($types) mysqli_stmt_bind_param($st, $types, ...$p);
    return mysqli_stmt_execute($st);
}

// Dropdown otomatis
function pilihan($nama, $opsi, $sel = "", $ket = "-- pilih --") {
    if ($sel !== "" && $sel !== null && !in_array($sel, $opsi)) $opsi[] = $sel;
    $h = "<select name='$nama' required><option value=''>" . e($ket) . "</option>";
    foreach ($opsi as $o) $h .= "<option " . ($o == $sel ? "selected" : "") . ">" . e($o) . "</option>";
    return $h . "</select>";
}
