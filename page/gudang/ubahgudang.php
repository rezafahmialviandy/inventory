<?php
$kode_barang = $_GET['kode_barang'];
$sql2 = $koneksi->query("SELECT * FROM gudang WHERE kode_barang = '$kode_barang'");
$tampil = $sql2->fetch_assoc();

$level = $tampil['level'];
?>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Ubah Barang</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="body">
          <form method="POST" enctype="multipart/form-data">
            <label for="">Kode Barang</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="kode_barang" class="form-control" id="kode_barang" value="<?php echo $tampil['kode_barang']; ?>" readonly />
              </div>
            </div>

            <label for="">Nama Barang</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="nama_barang" value="<?php echo $tampil['nama_barang']; ?>" class="form-control" />
              </div>
            </div>

            <label for="">Jenis Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="jenis_barang" class="form-control">
                  <?php
                  $sql = $koneksi->query("SELECT * FROM jenis_barang ORDER BY id");
                  while ($data = $sql->fetch_assoc()) {
                    $selected = ($tampil['jenis_barang'] == $data['jenis_barang']) ? 'selected' : '';
                    echo "<option value='$data[id].$data[jenis_barang]' $selected>$data[jenis_barang]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <label for="">Satuan Barang</label>
            <div class="form-group">
              <div class="form-line">
                <select name="satuan" class="form-control">
                  <?php
                  $sql = $koneksi->query("SELECT * FROM satuan ORDER BY id");
                  while ($data = $sql->fetch_assoc()) {
                    $selected = ($tampil['satuan'] == $data['satuan']) ? 'selected' : '';
                    echo "<option value='$data[id].$data[satuan]' $selected>$data[satuan]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- Menambahkan dropdown Supplier -->
            <label for="">Supplier</label>
            <div class="form-group">
              <div class="form-line">
                <select name="supplier" class="form-control">
                  <?php
                  $sql_supplier = $koneksi->query("SELECT * FROM tb_supplier ORDER BY nama_supplier");
                  while ($data_supplier = $sql_supplier->fetch_assoc()) {
                    $selected = ($tampil['supplier'] == $data_supplier['nama_supplier']) ? 'selected' : ''; // Menyesuaikan dengan nama_supplier yang ada
                    echo "<option value='$data_supplier[nama_supplier]' $selected>$data_supplier[nama_supplier]</option>";
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
            $jenis_barang = $pecah_jenis[1];

            // Satuan Barang
            $satuan_raw = $_POST['satuan'];
            $pecah_satuan = explode(".", $satuan_raw);
            $satuan = $pecah_satuan[1];

            // Supplier
            $supplier = $_POST['supplier'];  // Menyimpan nama_supplier

            // Query untuk update data
            $sql_update = $koneksi->query("UPDATE gudang SET nama_barang='$nama_barang', jenis_barang='$jenis_barang', satuan='$satuan', supplier='$supplier' WHERE kode_barang='$kode_barang'");

            if ($sql_update) {
              ?>
              <script type="text/javascript">
                alert("Data Berhasil Diubah");
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
