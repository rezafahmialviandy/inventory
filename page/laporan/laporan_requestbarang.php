<?php
// Koneksi Database
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Default
$bln = $_POST['bln'] ?? 'all';
$thn = $_POST['thn'] ?? date('Y');

// Ambil range tahun
$sql_tahun = $koneksi->query("SELECT MIN(YEAR(tanggal)) as tahun_awal, MAX(YEAR(tanggal)) as tahun_akhir FROM request_barang");
$row_tahun = $sql_tahun->fetch_assoc();
$tahun_awal = $row_tahun['tahun_awal'] ?? date('Y');
$tahun_akhir = $row_tahun['tahun_akhir'] ?? date('Y');

// Query data berdasarkan filter
$query = "SELECT * FROM request_barang WHERE YEAR(tanggal) = '$thn'";
if ($bln != 'all') {
    $query .= " AND MONTH(tanggal) = '$bln'";
}
$sql = $koneksi->query($query);
?>

<!-- Form untuk Filter dan Export -->
<form method="post" action="">
    <div class="row form-group">
        <!-- BULAN -->
        <div class="col-md-4">
            <select class="form-control" name="bln">
                <option value="all" <?= ($bln == 'all') ? 'selected' : ''; ?>>ALL</option>
                <?php
                $bulan_arr = [
                    1 => "January", 2 => "February", 3 => "March", 4 => "April",
                    5 => "May", 6 => "June", 7 => "July", 8 => "August",
                    9 => "September", 10 => "October", 11 => "November", 12 => "December"
                ];
                foreach ($bulan_arr as $key => $val) {
                    echo "<option value='$key' " . ($bln == $key ? 'selected' : '') . ">$val</option>";
                }
                ?>
            </select>
        </div>

        <!-- TAHUN -->
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

        <!-- TOMBOL -->
        <div class="col-md-5">
            <button type="submit" class="btn btn-primary" name="submit2">Tampilkan</button>
            <button type="submit" class="btn btn-danger" formaction="page/laporan/export_laporan_requestbarang_pdf.php" formtarget="_blank">Export to PDF</button>
        </div>
    </div>
</form>

<!-- TABEL HASIL -->
<div class="tampung1 mt-3">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%">
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
                if ($sql->num_rows > 0) {
                    while ($data = $sql->fetch_assoc()) {
                        echo "<tr>
                            <td>$no</td>
                            <td>{$data['id_request']}</td>
                            <td>{$data['tanggal']}</td>
                            <td>{$data['kode_barang']}</td>
                            <td>{$data['nama_barang']}</td>
                            <td>{$data['supplier']}</td>
                            <td>{$data['jumlah']}</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
