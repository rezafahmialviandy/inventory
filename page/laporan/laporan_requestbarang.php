<?php
// Koneksi Database
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Ambil filter bulan dan tahun dari form (POST)
$bln = isset($_POST['bln']) ? $_POST['bln'] : 'all';  // Default "all" jika tidak ada pilihan bulan
$thn = isset($_POST['thn']) ? $_POST['thn'] : date('Y'); // Default tahun sekarang jika tidak ada pilihan

// Query untuk mengambil data berdasarkan filter bulan dan tahun
$query = "SELECT * FROM request_barang WHERE YEAR(tanggal) = '$thn'";

if ($bln != 'all') {
    $query .= " AND MONTH(tanggal) = '$bln'";
}

$sql = $koneksi->query($query);
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

        <input type="submit" class="" name="submit" value="Export to PDF">
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

        <input type="submit" class="" name="submit2" value="Tampilkan">
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
                    <th>Pengirim</th>
                    <th>Jumlah Masuk</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($data = $sql->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['id_request'] ?></td>
                        <td><?php echo $data['tanggal'] ?></td>
                        <td><?php echo $data['kode_barang'] ?></td>
                        <td><?php echo $data['nama_barang'] ?></td>
                        <td><?php echo $data['pengirim'] ?></td>
                        <td><?php echo $data['jumlah'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

