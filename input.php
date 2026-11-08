
<?php
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_koperasi = $_POST['nama_koperasi'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    $sql = "INSERT INTO koperasi
            (nama_koperasi, kecamatan, alamat, longitude, latitude)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssdd",
        $nama_koperasi,
        $kecamatan,
        $alamat,
        $longitude,
        $latitude
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Data gagal disimpan: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
