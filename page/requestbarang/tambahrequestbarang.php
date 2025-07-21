<?php 
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Generate kode transaksi otomatis dengan tambahan elemen dinamis
$no = mysqli_query($koneksi, "select id_request from request_barang order by id_request desc limit 1");
$idtran = mysqli_fetch_array($no);
$bulan = date("m");
$tahun = date("y");
$prefix = "REQ"; // Prefix berdasarkan jenis request (misalnya 'REQ' untuk Request)

if ($idtran && $idtran['id_request']) {
    $kode = $idtran['id_request'];
    $urut = (int)substr($kode, 9, 3); // Mengambil urutan setelah prefix 'REQ-'
    $tambah = $urut + 1;
} else {
    $tambah = 1;
}

if(strlen($tambah) == 1){
    $format = $prefix."-".$bulan.$tahun."-00".$tambah; // Format ID dengan tambahan 2 digit urutan
} else if(strlen($tambah) == 2){
    $format = $prefix."-".$bulan.$tahun."-0".$tambah; // Format ID dengan tambahan 2 digit urutan
} else{
    $format = $prefix."-".$bulan.$tahun."-".$tambah; // Format ID dengan urutan 3 digit
}

$tanggal_request = date("Y-m-d");
?>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Request Barang</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="body">
          <form method="POST" enctype="multipart/form-data">

            <label for="">Id Request</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="id_request" class="form-control" id="id_request" value="<?php echo $format; ?>" readonly /> 
              </div>
            </div>

            <label for="">Tanggal Request</label>
            <div class="form-group">
              <div class="form-line">
                <input type="date" name="tanggal_request" class="form-control" id="tanggal_request" value="<?php echo $tanggal_request; ?>" required />
              </div>
            </div>

            <label for="">Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="barang" id="cmb_barang" class="form-control" required>
                  <option value="">-- Pilih Barang  --</option>
                  <?php
                  $sql = $koneksi->query("select * from gudang order by kode_barang");
                  while ($data = $sql->fetch_assoc()) {
                      echo "<option value='$data[kode_barang].$data[nama_barang]'>$data[kode_barang] | $data[nama_barang]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <label for="">Jumlah Request</label>
            <div class="form-group">
              <div class="form-line">
                <input type="number" name="jumlahrequest" id="jumlahrequest" onkeyup="sum()" class="form-control" required min="1" />
              </div>
            </div>

            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
          </form>

          <?php
        if (isset($_POST['simpan'])) {
            $id_request = $_POST['id_request'];
            $tanggal = $_POST['tanggal_request'];
            $barang = $_POST['barang'];
            $pecah_barang = explode(".", $barang);
            $kode_barang = $pecah_barang[0];
            $nama_barang = $pecah_barang[1];

            $jumlah = isset($_POST['jumlahrequest']) ? intval($_POST['jumlahrequest']) : 0;

            // Validasi minimum jumlah
            if ($jumlah < 1) {
                echo "<script>alert('Jumlah Request minimal 1!'); window.history.back();</script>";
            } else {
                // 1. Simpan ke request_barang
                $sql = $koneksi->query("INSERT INTO request_barang (id_request, tanggal, kode_barang, nama_barang, jumlah) 
                                        VALUES('$id_request','$tanggal','$kode_barang','$nama_barang','$jumlah')");

                if ($sql) {
                    echo "<script>alert('Simpan Data Berhasil'); window.location.href = '?page=requestbarang';</script>";
                }
            }
        }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
