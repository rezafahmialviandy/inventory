<?php
$id = $_GET['id'];
$sql2 = $koneksi->query("select * from jenis_barang where id = '$id'");
$tampil = $sql2->fetch_assoc();
?>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Ubah User</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="body">
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $tampil['id']; ?>">
            <label for="">Jenis Barang</label>
            <div class="form-group">
              <div class="form-line">
                <input type="text" name="jenis_barang" class="form-control" id="jenis_barang" value="<?php echo $tampil['jenis_barang']; ?>"  />
              </div>
            </div>
            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
          </form>
          <?php
          if (isset($_POST['simpan'])) {
            $id = $_POST['id'];
            $jenis_barang = $_POST['jenis_barang'];
            $sql = $koneksi->query("UPDATE jenis_barang SET jenis_barang='$jenis_barang' WHERE id='$id'");
            if ($sql) {
              ?>
              <script type="text/javascript">
                alert("Data Berhasil Diubah");
                window.location.href="?page=jenisbarang";
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
