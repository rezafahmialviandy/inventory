<?php 
$koneksi = new mysqli("localhost","pora5278_fahmi","Au1b839@@","pora5278_inventrizki");

// Cek kode barang terakhir, auto-generate format baru jika kosong
$no = mysqli_query($koneksi, "select kode_barang from gudang order by kode_barang desc limit 1");
$kdbarang = mysqli_fetch_array($no);

$bulan = date("m");
$tahun = date("y");

if ($kdbarang && $kdbarang['kode_barang']) {
    $kode = $kdbarang['kode_barang'];
    $urut = (int)substr($kode, 8, 3);
    $tambah = $urut + 1;
} else {
    $tambah = 1;
}

if(strlen($tambah) == 1){
    $format = "BAR-".$bulan.$tahun."00".$tambah;
} else if(strlen($tambah) == 2){
    $format = "BAR-".$bulan.$tahun."0".$tambah;
} else{
    $format = "BAR-".$bulan.$tahun.$tambah;
}

$jumlah = 0;
?>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Stok</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="body">
          <form method="POST" enctype="multipart/form-data">

            <label for="">Kode Barang</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="kode_barang" class="form-control" id="kode_barang" value="<?php echo $format; ?>" readonly />	 
              </div>
            </div>

            <label for="">Nama Barang</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="nama_barang" class="form-control" required />	 
              </div>
            </div>

            <label for="">Jenis Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="jenis_barang" class="form-control" required>
                  <option value="">-- Pilih Jenis Barang  --</option>
                  <?php
                  $sql = $koneksi->query("select * from jenis_barang order by id");
                  while ($data = $sql->fetch_assoc()) {
                    echo "<option value='$data[id].$data[jenis_barang]'>$data[jenis_barang]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <label for="">Satuan Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="satuan" class="form-control" required>
                  <option value="">-- Pilih Satuan Barang --</option>
                  <?php
                  $sql = $koneksi->query("select * from satuan order by id");
                  while ($data = $sql->fetch_assoc()) {
                    echo "<option value='$data[id].$data[satuan]'>$data[satuan]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>


            <label for="">Supplier</label>
            <div class="form-group">
              <div class="form-line">
                <select name="supplier" class="form-control" required>
                  <option value="">-- Pilih Supplier --</option>
                  <?php
                  // Query untuk mengambil data dari tabel tb_supplier
                  $sql_supplier = $koneksi->query("SELECT * FROM tb_supplier ORDER BY nama_supplier");
                  while ($data_supplier = $sql_supplier->fetch_assoc()) {
                    echo "<option value='$data_supplier[nama_supplier]'>$data_supplier[nama_supplier]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
          </form>

          <?php
            if (isset($_POST['simpan'])) {
              $kode_barang = $_POST['kode_barang'];
              $nama_barang = $_POST['nama_barang'];

              // Jenis Barang
              $jenis_barang_raw = $_POST['jenis_barang'];
              $pecah_jenis = explode(".", $jenis_barang_raw);
              $jenis_barang = isset($pecah_jenis[1]) ? $pecah_jenis[1] : '';

              // Satuan Barang
              $satuan_raw = $_POST['satuan'];
              $pecah_satuan = explode(".", $satuan_raw);
              $satuan = isset($pecah_satuan[1]) ? $pecah_satuan[1] : '';

              // Supplier
              $supplier = $_POST['supplier'];

              // Jumlah
              $jumlah = $_POST['jumlah'];
              if ($jumlah === "" || $jumlah === null) {
                $jumlah = 0;
              }

              // Menyimpan data ke tabel gudang
              $sql = $koneksi->query("INSERT INTO gudang (kode_barang, nama_barang, jenis_barang, jumlah, satuan, supplier) 
                        VALUES('$kode_barang','$nama_barang','$jenis_barang','$jumlah','$satuan','$supplier')");

              if ($sql) {
                ?>
                <script type="text/javascript">
                  alert("Data Berhasil Disimpan");
                  window.location.href = "?page=gudang";
                </script>
                <?php
              }
            }

          ?>
        </div>
      </div>
    </div>
  </div>
</div>
