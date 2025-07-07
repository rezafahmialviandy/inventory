



 
 <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Barang Keluar</h6>
            </div>
            <div class="card-body">
			
			 
	 	 	<table >
        <tr><td>
            LAPORAN PERBULAN DAN PERTAHUN
        </td></tr>
        <tr>
            <td width="50%">
<form action="page/laporan/export_laporan_barangkeluar_excel.php" method="post">
	<div class="row form-group">

		<div class="col-md-5">
		<select class="form-control " name="bln">
							
							
    						<option value="1" selected="">January</option>
    						<option value="2">February</option>
    						<option value="3">March</option>
    						<option value="4">April</option>
    						<option value="5">May</option>
    						<option value="6">June</option>
    						<option value="7">July</option>
    						<option value="8">August</option>
    						<option value="9">September</option>
    						<option value="10">October</option>
    						<option value="11">November</option>
    						<option value="12">December</option>
        			</select>
        		</div>
        		<div class="col-md-3">
<?php
// Ambil range tahun dari data transaksi barang keluar
$sql = $koneksi->query("SELECT 
    MIN(YEAR(tanggal)) as tahun_awal, 
    MAX(YEAR(tanggal)) as tahun_akhir 
FROM barang_keluar");
$row = $sql->fetch_assoc();

// Jika data kosong, fallback ke tahun sekarang
$tahun_awal = $row['tahun_awal'] ? $row['tahun_awal'] : date('Y');
$tahun_akhir = $row['tahun_akhir'] ? $row['tahun_akhir'] : date('Y');

// Cetak select tahun
echo "<select name='thn' class='form-control'>";
for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
    $selected = ($a == date('Y')) ? "selected" : "";
    echo "<option value='$a' $selected>$a</option>";
}
echo "</select>";
?>

</div>
        
	<input type="submit" class="" name="submit" value="Export to Excel">
	</div>
	</form>
	
	
	<form id="Myform2">
    <div class="row form-group">

        <div class="col-md-5">
        <select class="form-control " name="bln">
                            
                            <option value="all" selected="">ALL</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                    </select>
                </div>
                <div class="col-md-3">
<?php
// Ambil range tahun dari data transaksi barang keluar
$sql = $koneksi->query("SELECT 
    MIN(YEAR(tanggal)) as tahun_awal, 
    MAX(YEAR(tanggal)) as tahun_akhir 
FROM barang_keluar");
$row = $sql->fetch_assoc();

// Jika data kosong, fallback ke tahun sekarang
$tahun_awal = $row['tahun_awal'] ? $row['tahun_awal'] : date('Y');
$tahun_akhir = $row['tahun_akhir'] ? $row['tahun_akhir'] : date('Y');

// Cetak select tahun
echo "<select name='thn' class='form-control'>";
for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
    $selected = ($a == date('Y')) ? "selected" : "";
    echo "<option value='$a' $selected>$a</option>";
}
echo "</select>";
?>

</div>


    <input type="submit" class="" name="submit2"  value="Tampilkan">
    </div>
    </form>
    </td>
    
          
   </table>
	
	<div class="tampung2">
			
			
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
									$sql = $koneksi->query("select * from barang_keluar");
									while ($data = $sql->fetch_assoc()) {
										
									?>
									
                                        <tr>
                                            <td><?php echo $no++; ?></td>
											<td><?php echo $data['id_transaksi'] ?></td>
											<td><?php echo $data['tanggal'] ?></td>
											<td><?php echo $data['kode_barang'] ?></td>
											<td><?php echo $data['nama_barang'] ?></td>
											<td><?php echo $data['jumlah'] ?></td>

                                        </tr>
									<?php }?>

										   </tbody>
                                </table>
							
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>












