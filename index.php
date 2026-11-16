
<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "koperasi_bantul");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data koperasi
$sql = "SELECT * FROM koperasi";
$result = $conn->query($sql);

if (!$result) {
    die("Gagal mengambil data: " . $conn->error);
}

// Menyimpan data ke array
$data_koperasi = [];

while ($row = $result->fetch_assoc()) {
    $data_koperasi[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Web GIS Koperasi Kabupaten Bantul</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">

    <!-- Leaflet Measure CSS -->
    <link rel="stylesheet"
    href="https://unpkg.com/leaflet-measure@3.3.1/dist/leaflet-measure.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2, h3 {
            color: #333;
        }

        #map {
            width: 100%;
            height: 400px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: left;
            overflow-wrap: break-word;
        }

        th {
            background-color: #eeeeee;
            text-align: center;
        }

        td:nth-child(4),
        td:nth-child(5) {
            text-align: center;
            white-space: nowrap;
        }

        td:nth-child(6) {
            text-align: center;
        }

        a {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <!-- Judul halaman -->
    <h2>Web GIS Koperasi Aktif Kabupaten Bantul</h2>

    <!-- Bagian peta -->
    <h3>Peta Persebaran Koperasi Kabupaten Bantul</h3>

    <div id="map"></div>

    <hr>

    <!-- Bagian tabel data -->
    <h3>Data Koperasi</h3>

    <p>
        <a href="input/index.html">Tambah Data</a>
    </p>

    <table>

        <!-- Mengatur proporsi lebar kolom -->
        <colgroup>
            <col style="width: 28%;">
            <col style="width: 12%;">
            <col style="width: 30%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
        </colgroup>

        <tr>
            <th>Nama Koperasi</th>
            <th>Kecamatan</th>
            <th>Alamat</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($data_koperasi) > 0): ?>

            <?php foreach ($data_koperasi as $row): ?>

                <tr>
                    <td>
                        <?php echo htmlspecialchars($row['nama_koperasi']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['kecamatan']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['alamat']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['longitude']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['latitude']); ?>
                    </td>

                    <td>
                        <a href="edit/index.php?id=<?php echo $row['id']; ?>">
                            Edit
                        </a>
                        |
                        <a href="delete.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">Data belum tersedia</td>
            </tr>

        <?php endif; ?>

    </table>


    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>

    <!-- Leaflet Measure JavaScript -->
    <script src="https://unpkg.com/leaflet-measure@3.3.1/dist/leaflet-measure.js"></script>

    <script>

        // Mengambil data koperasi dari PHP
        var dataKoperasi = <?php
            echo json_encode(
                $data_koperasi,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP
            );
        ?>;

        // Membuat peta awal
        var map = L.map('map').setView([-7.88, 110.35], 11);

        // Membuat basemap OpenStreetMap
        var osm = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap contributors'
            }
        );

        // Membuat basemap Esri Imagery
        var esri = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Tiles &copy; Esri'
            }
        );

        // Menampilkan basemap awal
        osm.addTo(map);

        // Membuat kelompok layer marker
        var markerKoperasi = L.layerGroup();

        // Menyimpan koordinat marker
        var lokasiMarker = [];

        // Menampilkan marker berdasarkan database
        dataKoperasi.forEach(function(data) {

            var longitude = parseFloat(data.longitude);
            var latitude = parseFloat(data.latitude);

            if (
                Number.isFinite(latitude) &&
                Number.isFinite(longitude) &&
                latitude >= -90 &&
                latitude <= 90 &&
                longitude >= -180 &&
                longitude <= 180
            ) {

                // Membuat marker koperasi
                var marker = L.marker([
                    latitude,
                    longitude
                ]);

                // Membuat popup informasi
                var isiPopup = document.createElement('div');

                var nama = document.createElement('b');
                nama.textContent = data.nama_koperasi;

                var kecamatan = document.createElement('p');
                kecamatan.textContent =
                    'Kecamatan: ' + data.kecamatan;

                var alamat = document.createElement('p');
                alamat.textContent =
                    'Alamat: ' + data.alamat;

                isiPopup.appendChild(nama);
                isiPopup.appendChild(kecamatan);
                isiPopup.appendChild(alamat);

                marker.bindPopup(isiPopup);

                // Memasukkan marker ke kelompok layer
                markerKoperasi.addLayer(marker);

                lokasiMarker.push([latitude, longitude]);
            }

        });

        // Menampilkan kelompok marker
        markerKoperasi.addTo(map);

        // Pengaturan pilihan basemap
        var baseMaps = {
            "OpenStreetMap": osm,
            "Esri Imagery": esri
        };

        // Pengaturan layer tambahan
        var overlayMaps = {
            "Lokasi Koperasi": markerKoperasi
        };

        // Menambahkan Layer Control
        L.control.layers(
            baseMaps,
            overlayMaps,
            {
                collapsed: false
            }
        ).addTo(map);

        // Menambahkan Scale Bar
        L.control.scale({
            maxWidth: 150,
            position: 'bottomright'
        }).addTo(map);

        // Menambahkan Measurement Tool
        L.control.measure({
            position: 'topleft',
            primaryLengthUnit: 'meters',
            secondaryLengthUnit: 'kilometers',
            primaryAreaUnit: 'sqmeters',
            secondaryAreaUnit: 'hectares'
        }).addTo(map);

        // Menyesuaikan tampilan berdasarkan lokasi marker
        if (lokasiMarker.length > 0) {
            map.fitBounds(lokasiMarker, {
                padding: [30, 30]
            });
        }

    </script>

</body>
</html>

<?php
$conn->close();
?>
