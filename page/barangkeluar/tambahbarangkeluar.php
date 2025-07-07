<script>
function sum() {
    var stok = document.getElementById('stok') ? document.getElementById('stok').value : 0;
    var jumlahkeluar = document.getElementById('jumlahkeluar').value;
    var result = parseInt(stok) - parseInt(jumlahkeluar);
    if (!isNaN(result)) {
        document.getElementById('sisa').value = result;
    }
}
</script>

<?php 
$koneksi = new mysqli("localhost","pora5278_fahmi","Au1b839@@","pora5278_inventrizki");

// Generate kode transaksi otomatis
$no = mysqli_query($koneksi, "select id_transaksi from barang_keluar order by id_transaksi desc limit 1");
$idtran = mysqli_fetch_array($no);

$bulan = date("m");
$tahun = date("y");
if ($idtran && $idtran['id_transaksi']) {
    $kode = $idtran['id_transaksi'];
    $urut = (int)substr($kode, 8, 3);
    $tambah = $urut + 1;
} else {
    $tambah = 1;
}

if(strlen($tambah) == 1){
    $format = "TRK-".$bulan.$tahun."00".$tambah;
} else if(strlen($tambah) == 2){
    $format = "TRK-".$bulan.$tahun."0".$tambah;
} else{
    $format = "TRK-".$bulan.$tahun.$tambah;
}

$tanggal_keluar = date("Y-m-d");
?>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Keluar</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="body">
          <form method="POST" enctype="multipart/form-data">

            <label for="">Id Transaksi</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="id_transaksi" class="form-control" id="id_transaksi" value="<?php echo $format; ?>" readonly />  
              </div>
            </div>

            <label for="">Tanggal Keluar</label>
            <div class="form-group">
              <div class="form-line">
                <input type="date" name="tanggal_keluar" class="form-control" id="tanggal_keluar" value="<?php echo $tanggal_keluar; ?>" required />
              </div>
            </div>

            <label for="">Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="barang" id="cmb_barang" class="form-control" onchange="updateStok()" required>
                  <option value="">-- Pilih Barang  --</option>
                  <?php
                  $sql = $koneksi->query("select * from gudang order by kode_barang");
                  while ($data = $sql->fetch_assoc()) {
                      echo "<option value='$data[kode_barang].$data[nama_barang].$data[satuan]' data-stok='".$data['jumlah']."'>".$data['kode_barang']." | ".$data['nama_barang']."</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <input type="hidden" name="satuan" id="satuan">
            <input type="hidden" name="stok" id="stok">

            <div class="tampung"></div>

            <label for="">Jumlah</label>
            <div class="form-group">
              <div class="form-line">
                <input type="number" name="jumlahkeluar" id="jumlahkeluar" onkeyup="sum()" class="form-control" min="1" required />
              </div>
            </div>

            <label for="sisa">Sisa Stok</label>
            <div class="form-group">
              <div class="form-line">
                <input readonly="readonly" name="sisa" id="sisa" type="number" class="form-control">
              </div>
            </div>

            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
          </form>

<script>
function updateStok() {
    var select = document.getElementById('cmb_barang');
    var stok = 0;
    var satuan = "";
    if(select.value) {
        var selected = select.options[select.selectedIndex];
        stok = selected.getAttribute('data-stok');
        var valArr = select.value.split(".");
        if(valArr.length > 2) satuan = valArr[2];
    }
    document.getElementById('stok').value = stok;
    document.getElementById('satuan').value = satuan;
    document.getElementById('jumlahkeluar').value = "";
    document.getElementById('sisa').value = "";
}
</script>

<?php
if (isset($_POST['simpan'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $tanggal = $_POST['tanggal_keluar'];
    $barang = $_POST['barang'];
    $pecah_barang = explode(".", $barang);
    $kode_barang = $pecah_barang[0];
    $nama_barang = $pecah_barang[1];
    $satuan = isset($pecah_barang[2]) ? $pecah_barang[2] : '';
    $jumlah = $_POST['jumlahkeluar'];
    $stok = isset($_POST['stok']) ? $_POST['stok'] : 0;
    $sisa = isset($_POST['sisa']) ? $_POST['sisa'] : 0;

    // Validasi jumlah tidak melebihi stok
    if ($jumlah > $stok || $sisa < 0) {
        echo "<script>
        alert('Stok Barang Tidak Cukup, Transaksi Tidak Dapat Dilakukan');
        window.location.href='?page=barangkeluar&aksi=tambahbarangkeluar';
        </script>";
    } else {
        // Simpan transaksi barang keluar TANPA field total!
        $sql = $koneksi->query("INSERT INTO barang_keluar (id_transaksi, tanggal, kode_barang, nama_barang, jumlah) VALUES('$id_transaksi','$tanggal','$kode_barang','$nama_barang','$jumlah')");
        // Update stok di gudang
        $sql2 = $koneksi->query("UPDATE gudang SET jumlah=jumlah-$jumlah WHERE kode_barang='$kode_barang'");

        if ($sql && $sql2) {
            echo "<script>
            alert('Simpan Data Berhasil');
            window.location.href='?page=barangkeluar';
            </script>";
        } else {
            echo "<script>
            alert('Gagal Simpan Data!');
            </script>";
        }
    }
}
?>
        </div>
      </div>
    </div>
  </div>
</div>
