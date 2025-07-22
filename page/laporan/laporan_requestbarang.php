<?php
// Koneksi Database
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Inisialisasi variabel filter
$bln = 'all';
$thn = date('Y');

// Ambil filter dari POST jika form di-submit
if (isset($_POST['submit2'])) {
    $bln = isset($_POST['bln']) ? $_POST['bln'] : 'all';
    $thn = isset($_POST['thn']) ? $_POST['thn'] : date('Y');
}

// Query untuk mengambil data berdasarkan filter bulan dan tahun
$query = "SELECT * FROM request_barang WHERE YEAR(tanggal) = ?";
$params = [$thn];
$types = "s";

if ($bln != 'all') {
    $query .= " AND MONTH(tanggal) = ?";
    $params[] = $bln;
    $types .= "s";
}

$query .= " ORDER BY tanggal DESC";

// Gunakan prepared statement untuk keamanan
$stmt = $koneksi->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Form untuk Export PDF -->
<form action="page/laporan/export_laporan_requestbarang_pdf.php" method="post">
    <div class="row form-group">
        <div class="col-md-5">
            <select class="form-control" name="bln">
                <option value="all" <?php echo ($bln == 'all') ? 'selected' : ''; ?>>ALL</option>
                <option value="1" <?php echo ($bln == '1') ? 'selected' : ''; ?>>January</option>
                <option value="2" <?php echo ($bln == '2') ? 'selected' : ''; ?>>February</option>
                <option value="3" <?php echo ($bln == '3') ? 'selected' : ''; ?>>March</option>
                <option value="4" <?php echo ($bln == '4') ? 'selected' : ''; ?>>April</option>
                <option value="5" <?php echo ($bln == '5') ? 'selected' : ''; ?>>May</option>
                <option value="6" <?php echo ($bln == '6') ? 'selected' : ''; ?>>June</option>
                <option value="7" <?php echo ($bln == '7') ? 'selected' : ''; ?>>July</option>
                <option value="8" <?php echo ($bln == '8') ? 'selected' : ''; ?>>August</option>
                <option value="9" <?php echo ($bln == '9') ? 'selected' : ''; ?>>September</option>
                <option value="10" <?php echo ($bln == '10') ? 'selected' : ''; ?>>October</option>
                <option value="11" <?php echo ($bln == '11') ? 'selected' : ''; ?>>November</option>
                <option value="12" <?php echo ($bln == '12') ? 'selected' : ''; ?>>December</option>
            </select>
        </div>

        <div class="col-md-3">
            <?php
            // Ambil range tahun dari data transaksi barang masuk
            $sql_tahun = $koneksi->query("SELECT MIN(YEAR(tanggal)) as tahun_awal, MAX(YEAR(tanggal)) as tahun_akhir FROM request_barang");
            $row_tahun = $sql_tahun->fetch_assoc();
            $tahun_awal = $row_tahun['tahun_awal'] ? $row_tahun['tahun_awal'] : date('Y');
            $tahun_akhir = $row_tahun['tahun_akhir'] ? $row_tahun['tahun_akhir'] : date('Y');
            ?>
            <select name="thn" class="form-control">
                <?php
                for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
                    $selected = ($a == $thn) ? "selected" : "";
                    echo "<option value='$a' $selected>$a</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" class="btn btn-primary" name="submit" value="Export to PDF">
    </div>
</form>

<!-- Form untuk menampilkan data -->
<form id="Myform1" method="post">
    <div class="row form-group">
        <div class="col-md-5">
            <select class="form-control" name="bln">
                <option value="all" <?php echo ($bln == 'all') ? 'selected' : ''; ?>>ALL</option>
                <option value="1" <?php echo ($bln == '1') ? 'selected' : ''; ?>>January</option>
                <option value="2" <?php echo ($bln == '2') ? 'selected' : ''; ?>>February</option>
                <option value="3" <?php echo ($bln == '3') ? 'selected' : ''; ?>>March</option>
                <option value="4" <?php echo ($bln == '4') ? 'selected' : ''; ?>>April</option>
                <option value="5" <?php echo ($bln == '5') ? 'selected' : ''; ?>>May</option>
                <option value="6" <?php echo ($bln == '6') ? 'selected' : ''; ?>>June</option>
                <option value="7" <?php echo ($bln == '7') ? 'selected' : ''; ?>>July</option>
                <option value="8" <?php echo ($bln == '8') ? 'selected' : ''; ?>>August</option>
                <option value="9" <?php echo ($bln == '9') ? 'selected' : ''; ?>>September</option>
                <option value="10" <?php echo ($bln == '10') ? 'selected' : ''; ?>>October</option>
                <option value="11" <?php echo ($bln == '11') ? 'selected' : ''; ?>>November</option>
                <option value="12" <?php echo ($bln == '12') ? 'selected' : ''; ?>>December</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="thn" class="form-control">
                <?php
                for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
                    $selected = ($a == $thn) ? "selected" : "";
                    echo "<option value='$a' $selected>$a</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" class="btn btn-success" name="submit2" value="Tampilkan">
    </div>
</form>

<!-- Tabel untuk Menampilkan Data -->
<div class="tampung1">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Request</th>
                    <th>Tanggal Masuk</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Supplier</th>
                    <th>Jumlah Masuk</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($data = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($data['id_request']); ?></td>
                        <td><?php echo htmlspecialchars($data['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($data['kode_barang']); ?></td>
                        <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($data['supplier']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>