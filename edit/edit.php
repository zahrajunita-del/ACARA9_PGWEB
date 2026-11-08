<?php
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $nama_koperasi = $_POST['nama_koperasi'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    $sql = "UPDATE koperasi SET
            nama_koperasi = ?,
            kecamatan = ?,
            alamat = ?,
            longitude = ?,
            latitude = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssddi",
        $nama_koperasi,
        $kecamatan,
        $alamat,
        $longitude,
        $latitude,
        $id
    );

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Data gagal diperbarui: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "Permintaan tidak valid.";
}

$conn->close();
?>