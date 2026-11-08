
<?php
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM koperasi WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Data gagal dihapus: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "ID data tidak ditemukan.";
}

$conn->close();
?>
