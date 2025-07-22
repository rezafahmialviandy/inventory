<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Tangkap filter dari form
$bln = isset($_POST['bln']) ? $_POST['bln'] : 'all';
$thn = isset($_POST['thn']) ? $_POST['thn'] : date('Y');

// Ambil range tahun dari data
$sql_tahun = $koneksi->query("SELECT MIN(YEAR(tanggal)) as tahun_awal, MAX(YEAR(tanggal)) as tahun_akhir FROM barang_keluar");
$row_tahun = $sql_tahun->fetch_assoc();
$tahun_awal = $row_tahun['tahun_awal'] ?? date('Y');
$tahun_akhir = $row_tahun['tahun_akhir'] ?? date('Y');

// Query data berdasarkan filter
$query = "SELECT * FROM barang_keluar WHERE YEAR(tanggal) = '$thn'";
if ($bln != 'all') {
    $query .= " AND MONTH(tanggal) = '$bln'";
}
$sql = $koneksi->query($query);

// Array bulan
$bulan_arr = [
    1 => "January", 2 => "February", 3 => "March", 4 => "April",
    5 => "May", 6 => "June", 7 => "July", 8 => "August",
    9 => "September", 10 => "October", 11 => "November", 12 => "December"
];
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Barang Keluar</h6>
    </div>
    <div class="card-body">

      <form method="post">
        <div class="row form-group align-items-center">
          <div class="col-md-4">
            <select class="form-control" name="bln" onchange="this.form.submit()">
              <option value="all" <?= ($bln == 'all') ? 'selected' : ''; ?>>ALL</option>
              <?php
              foreach ($bulan_arr as $key => $val) {
                echo "<option value='$key'" . ($bln == $key ? ' selected' : '') . ">$val</option>";
              }
              ?>
            </select>
          </div>

          <div class="col-md-2">
            <select name="thn" class="form-control" onchange="this.form.submit()">
              <?php
              for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
                $selected = ($a == $thn) ? "selected" : "";
                echo "<option value='$a' $selected>$a</option>";
              }
              ?>
            </select>
          </div>

          <div class="col-md-auto">
            <button type="submit"
                    class="btn btn-success"
                    formaction="page/laporan/export_laporan_barangkeluar_pdf.php"
                    formtarget="_blank">
              Export to PDF
            </button>
          </div>
        </div>
      </form>

      <!-- TABEL HASIL -->
      <div class="tampung2 mt-3">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Id Transaksi</th>
                <th>Tanggal Keluar</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Keluar</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              if ($sql->num_rows > 0) {
                while ($data = $sql->fetch_assoc()) {
                  echo "<tr>
                          <td>{$no}</td>
                          <td>{$data['id_transaksi']}</td>
                          <td>{$data['tanggal']}</td>
                          <td>{$data['kode_barang']}</td>
                          <td>{$data['nama_barang']}</td>
                          <td>{$data['jumlah']}</td>
                        </tr>";
                  $no++;
                }
              } else {
                echo "<tr><td colspan='6' class='text-center'>Tidak ada data ditemukan.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
