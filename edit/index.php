<?php
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = $_GET['id'] ?? '';

if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID data tidak valid.");
}

$sql = "SELECT * FROM koperasi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data koperasi tidak ditemukan.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Koperasi</title>
</head>
<body>

<h2>Form Edit Data Koperasi</h2>

<form action="edit.php" method="POST">

    <input type="hidden" name="id"
           value="<?php echo $row['id']; ?>">

    Nama Koperasi:<br>
    <input type="text" name="nama_koperasi"
           value="<?php echo htmlspecialchars($row['nama_koperasi']); ?>" required>
    <br><br>

    Kecamatan:<br>
    <input type="text" name="kecamatan"
           value="<?php echo htmlspecialchars($row['kecamatan']); ?>" required>
    <br><br>

    Alamat:<br>
    <textarea name="alamat" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
    <br><br>

    Longitude:<br>
    <input type="number" step="any" name="longitude"
           value="<?php echo htmlspecialchars($row['longitude']); ?>" required>
    <br><br>

    Latitude:<br>
    <input type="number" step="any" name="latitude"
           value="<?php echo htmlspecialchars($row['latitude']); ?>" required>
    <br><br>

    <button type="submit">Simpan Perubahan</button>

</form>

<br>
<a href="../index.php">Kembali</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>