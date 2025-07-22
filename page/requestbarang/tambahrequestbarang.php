<?php 
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Generate kode transaksi otomatis
$no = mysqli_query($koneksi, "SELECT id_request FROM request_barang ORDER BY id_request DESC LIMIT 1");
$idtran = mysqli_fetch_array($no);
$bulan = date("m");
$tahun = date("y");
$prefix = "REQ";

if ($idtran && $idtran['id_request']) {
    $kode = $idtran['id_request'];
    $urut = (int)substr($kode, 9, 3);
    $tambah = $urut + 1;
} else {
    $tambah = 1;
}

if(strlen($tambah) == 1){
    $format = $prefix."-".$bulan.$tahun."-00".$tambah;
} else if(strlen($tambah) == 2){
    $format = $prefix."-".$bulan.$tahun."-0".$tambah;
} else{
    $format = $prefix."-".$bulan.$tahun."-".$tambah;
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
                <input type="text" name="id_request" class="form-control" value="<?php echo $format; ?>" readonly /> 
              </div>
            </div>

            <label for="">Tanggal Request</label>
            <div class="form-group">
              <div class="form-line">
                <input type="date" name="tanggal_request" class="form-control" value="<?php echo $tanggal_request; ?>" required />
              </div>
            </div>

            <label for="">Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="barang" id="cmb_barang" class="form-control" required>
                  <option value="">-- Pilih Barang  --</option>
                  <?php
                  $sql = $koneksi->query("SELECT * FROM gudang ORDER BY kode_barang");
                  while ($data = $sql->fetch_assoc()) {
                      echo "<option value='{$data['kode_barang']}.{$data['nama_barang']}' data-supplier='{$data['supplier']}'>
                              {$data['kode_barang']} | {$data['nama_barang']}
                            </option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <label for="">Supplier</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="supplier" id="supplier" class="form-control" readonly />
              </div>
            </div>

            <label for="">Jumlah Request</label>
            <div class="form-group">
              <div class="form-line">
                <input type="number" name="jumlahrequest" id="jumlahrequest" class="form-control" required min="1" />
              </div>
            </div>

            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
          </form>

          <?php
          if (isset($_POST['simpan'])) {
              $id_request = $_POST['id_request'];
              $tanggal = $_POST['tanggal_request'];
              $barang = $_POST['barang'];
              $supplier = $_POST['supplier'];

              $pecah_barang = explode(".", $barang);
              $kode_barang = $pecah_barang[0];
              $nama_barang = $pecah_barang[1];

              $jumlah = isset($_POST['jumlahrequest']) ? intval($_POST['jumlahrequest']) : 0;

              if ($jumlah < 1) {
                  echo "<script>alert('Jumlah Request minimal 1!'); window.history.back();</script>";
              } else {
                  // INSERT ke database
                  $sql = $koneksi->query("INSERT INTO request_barang (id_request, tanggal, kode_barang, nama_barang, jumlah, supplier) 
                                          VALUES('$id_request','$tanggal','$kode_barang','$nama_barang','$jumlah','$supplier')");

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

<!-- Script auto-isi supplier -->
<script>
document.getElementById("cmb_barang").addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    const supplier = selected.getAttribute("data-supplier");
    document.getElementById("supplier").value = supplier || "";
});
</script>
