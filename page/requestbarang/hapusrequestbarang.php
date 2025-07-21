 <?php
 
 $id_request = $_GET['id_request'];
 $sql = $koneksi->query("delete from request_barang where id_request = '$id_request'");

 if ($sql) {
 
 ?>
 
 
	<script type="text/javascript">
	alert("Data Berhasil Dihapus");
	window.location.href="?page=requestbarang";
	</script>
	
 <?php
 
 }
 
 ?>