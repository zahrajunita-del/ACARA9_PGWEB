
<?php
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM koperasi";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Koperasi Kabupaten Bantul</title>
</head>
<body>

    <h2>Data Koperasi Aktif Kabupaten Bantul</h2>

    <a href="input/index.html">Tambah Data</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Nama Koperasi</th>
            <th>Kecamatan</th>
            <th>Alamat</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Aksi</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>

        <tr>
            <td><?php echo htmlspecialchars($row['nama_koperasi']); ?></td>
            <td><?php echo htmlspecialchars($row['kecamatan']); ?></td>
            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td><?php echo htmlspecialchars($row['longitude']); ?></td>
            <td><?php echo htmlspecialchars($row['latitude']); ?></td>

            <td>
                <a href="edit/index.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                   Hapus
                </a>
            </td>
        </tr>

        <?php
            }
        } else {
            echo "<tr><td colspan='6'>Data belum tersedia</td></tr>";
        }
        ?>

    </table>

</body>
</html>

<?php
$conn->close();
?>
