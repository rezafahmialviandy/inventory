
<?php
	
	mysqli_report (MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	
	$koneksi = new mysqli("localhost","pora5278_fahmi","Au1b839@@","pora5278_inventrizki");

	
if(empty($_SESSION['admin'])){
    
    header("location:index.php");
  }


	
	
	?>	



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
	<link rel="icon" type="image/x-icon" href="img/dkriuk.jpg">
  <title>Inventory Barang</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  
  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
 

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index3.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <!-- <i class="fas fa-building"></i> -->
        </div>
        <div class="sidebar-brand-text mx-2">
          <img src="img/logodkriuk.png" alt="Logo Image" width="150px"/>

        </div>
      </a>

	  <!-- Divider -->
      <hr class="sidebar-divider my-0">
	  

 <?php
   if ($_SESSION['admin']) {
	   $user = $_SESSION['admin'];
   }
   $sql =$koneksi->query("select * from users where id='$user'");
   $data = $sql->fetch_assoc();
   ?>

  

  <!--sidebar start-->

    <li class="d-flex align-items-center justify-content-center">
        <a class="nav-link">
		 <img src="img/<?php echo $data['foto']?>" class="img-circle" width="80" alt="User"/></a>
		  <li class="d-flex align-items-center justify-content-center">
		  </li>
	  </li>
		 <li class="nav-item ">
        <a class="nav-link">
         	<div class="d-flex align-items-center justify-content-center" class="name">  <?php echo  $data['nama'];?></div></font>
			<div class="d-flex align-items-center justify-content-center" class="email">Anda adalah <?php echo $data['level'];?></div>
		 </a>
      </li>
	



      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="?page=home3">
          <i class="fas fa-fw fa-home"></i>
          <span>Home</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Pilih Menu
      </div>
	 
      <!-- Nav Item - Pages Collapse Menu -->
	  
	  
	    <li class="nav-item active">
        <a class="nav-link" href="?page=pengguna">
          <i class="fas fa-fw fa-home"></i>
          <span>Data Pengguna</span></a>
      </li>
	  
	  
	   <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData" aria-expanded="true" aria-controls="collapseData">
          <i class="fas fa-fw fa-folder"></i>
          <span>Data Master</span>
        </a>
        <div id="collapseData" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu:</h6>
            <a class="collapse-item" href="?page=gudang">Data Barang</a>
            <a class="collapse-item" href="?page=jenisbarang">Jenis Barang</a>
            <a class="collapse-item" href="?page=satuanbarang">Satuan Barang</a>
			 <a class="collapse-item" href="?page=supplier">Data Supplier</a>
           
          </div>
        </div>
      </li>
	  
	
	  
	    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Transaksi</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu:</h6>
            <a class="collapse-item" href="?page=barangmasuk">Barang Masuk</a>
            <a class="collapse-item" href="?page=barangkeluar">Barang Keluar</a>
           
           
          </div>
        </div>
      </li>



      	<li class="nav-item active">
        <a class="nav-link" href="?page=requestbarang" aria-expanded="true">
          <i class="fas fa-fw fa-folder"></i>
          <span>Request Barang</span>
        </a>
      </li>
	  
	  
	      <!-- Heading -->
      <div class="sidebar-heading">
        Laporan
      </div>
	  
	  
      
	     <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan" aria-expanded="true" aria-controls="collapseLaporan">
          <i class="fas fa-fw fa-folder"></i>
          <span>Laporan</span>
        </a>
        <div id="collapseLaporan" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Menu Laporan:</h6>
            <a class="collapse-item" href="?page=laporan_supplier">Laporan Supplier</a>
            <a class="collapse-item" href="?page=laporan_barangmasuk">Laporan Barang Masuk</a>
            <a class="collapse-item" href="?page=laporan_gudang">Laporan Stok Gudang</a>
            <a class="collapse-item" href="?page=laporan_barangkeluar">Laporan Barang Keluar</a> 
            <a class="collapse-item" href="?page=laporan_requestbarang">Laporan Request Barang</a> 
          </div>
        </div>
      </li>
	  
	  
	  
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

		<!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
			 <div class="top-menu">
        <ul class="nav pull-right top-menu">
		
           <li><a onclick="return confirm('Apakah anda yakin akan logout?')" class="btn btn-danger" class="logout" href="logout.php">Logout</a></li>
        </ul>
      </div>
             
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
		
		 <section class="content">
	
	
		      <?php
			   $page = $_GET['page'];
			   $aksi = $_GET['aksi'];
			   
			   
			   	if ($page == "pengguna") {
				   if ($aksi == "") {
					   include "page/pengguna/pengguna.php";
				   }
				    if ($aksi == "tambahpengguna") {
					   include "page/pengguna/tambahpengguna.php";
				   }
				    if ($aksi == "ubahpengguna") {
					   include "page/pengguna/ubahpengguna.php";
				   }
				   
				    if ($aksi == "hapuspengguna") {
					   include "page/pengguna/hapuspengguna.php";
				   }
			   }
			   
			   
			   if ($page == "supplier") {
				   if ($aksi == "") {
					   include "page/supplier/supplier.php";
				   }
				    if ($aksi == "tambahsupplier") {
					   include "page//supplier/tambahsupplier.php";
				   }
				    if ($aksi == "ubahsupplier") {
					   include "page/supplier/ubahsupplier.php";
				   }
				   
				    if ($aksi == "hapussupplier") {
					   include "page/supplier/hapussupplier.php";
				   }
			   }
			   
			    
			   if ($page == "jenisbarang") {
				   if ($aksi == "") {
					   include "page/jenisbarang/jenisbarang.php";
				   }
				    if ($aksi == "tambahjenis") {
					   include "page/jenisbarang/tambahjenis.php";
				   }
				    if ($aksi == "ubahjenis") {
					   include "page/jenisbarang/ubahjenis.php";
				   } 
				    if ($aksi == "hapusjenis") {
					   include "page/jenisbarang/hapusjenis.php";
				   }
			   }
			   
			     if ($page == "satuanbarang") {
				   if ($aksi == "") {
					   include "page/satuanbarang/satuan.php";
				   }
				    if ($aksi == "tambahsatuan") {
					   include "page//satuanbarang/tambahsatuan.php";
				   }
				    if ($aksi == "ubahsupplier") {
					   include "page/supplier/ubahsupplier.php";
				   }
				   
				    if ($aksi == "hapussupplier") {
					   include "page/supplier/hapussupplier.php";
				   }
			   }
	
	
	
	
				   if ($page == "barangmasuk") {
				   if ($aksi == "") {
					   include "page/barangmasuk/barangmasuk.php";
				   }
				    if ($aksi == "tambahbarangmasuk") {
					   include "page/barangmasuk/tambahbarangmasuk.php";
				   }
				    if ($aksi == "hapusbarangmasuk") {
					   include "page/barangmasuk/hapusbarangmasuk.php";
				   }
			   }


         	if ($page == "requestbarang") {
				   if ($aksi == "") {
					   include "page/requestbarang/requestbarang.php";
				   }
				    if ($aksi == "tambahrequestbarang") {
					   include "page/requestbarang/tambahrequestbarang.php";
				   }
				    if ($aksi == "hapusrequestbarang") {
					   include "page/requestbarang/hapusrequestbarang.php";
				   }
			   }
	
	
				if ($page == "gudang") {
				   if ($aksi == "") {
					   include "page/gudang/gudang.php";
				   }
				    if ($aksi == "tambahgudang") {
					   include "page/gudang/tambahgudang.php";
				   }
				    if ($aksi == "ubahgudang") {
					   include "page/gudang/ubahgudang.php";
				   }
				   
				    if ($aksi == "hapusgudang") {
					   include "page/gudang/hapusgudang.php";
				   }
				}
				
				
				   if ($page == "barangkeluar") {
				   if ($aksi == "") {
					   include "page/barangkeluar/barangkeluar.php";
				   }
				    if ($aksi == "tambahbarangkeluar") {
					   include "page/barangkeluar/tambahbarangkeluar.php";
				   }
				    if ($aksi == "hapusbarangkeluar") {
					   include "page/barangkeluar/hapusbarangkeluar.php";
				   }
			   }
				
				
			      if ($page == "laporan_supplier") {
				   if ($aksi == "") {
					   include "page/laporan/laporan_supplier.php";
				   }
				  }
				    if ($page == "laporan_barangmasuk") {
				   if ($aksi == "") {
					   include "page/laporan/laporan_barangmasuk.php";
				   }
					}
					
				   if ($page == "laporan_gudang") {
				   if ($aksi == "") {
					   include "page/laporan/laporan_gudang.php";
				   }   
			   }
				    if ($page == "laporan_barangkeluar") {
				   if ($aksi == "") {
					   include "page/laporan/laporan_barangkeluar.php";
				   }
					}

				    if ($page == "laporan_requestbarang") {
				   if ($aksi == "") {
					   include "page/laporan/laporan_requestbarang.php";
				   }
					}
			   
			     
			   if ($page == "") {
				   include "home3.php";
			   }
			   if ($page == "home3") {
				   include "home3.php";
			   }
			   ?>
    

    </section>

 
</div>
      <!-- End of Main Content -->


  </style>
   <!-- Footer -->

      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
      <footer class="sticky-footer bg-white" style="">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright  &copy; Inventory System 2025 | Created by <a href='https://dkriuk.com/' title='Drkiuk' target='_blank'>Dkriuk Bekasi</a>
            </span>
          </div>
        </div>
      </footer>
  </div>
  
  <!-- End of Page Wrapper -->
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->

 <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
  
    <!--script for this page-->
<script>
jQuery(document).ready(function($) {
   $('#cmb_barang').change(function() { // Jika Select Box id provinsi dipilih
     var tamp = $(this).val(); // Ciptakan variabel provinsi
     $.ajax({
            type: 'POST', // Metode pengiriman data menggunakan POST
          url: 'page/barangmasuk/get_barang.php', // File yang akan memproses data
         data: 'tamp=' + tamp, // Data yang akan dikirim ke file pemroses
         success: function(data) { // Jika berhasil
              $('.tampung').html(data); // Berikan hasil ke id kota
            }
           
     
    });
});
});
</script>			

<script>
jQuery(document).ready(function($) {
   $('#cmb_barang').change(function() { // Jika Select Box id provinsi dipilih
     var tamp = $(this).val(); // Ciptakan variabel provinsi
     $.ajax({
            type: 'POST', // Metode pengiriman data menggunakan POST
          url: 'page/barangmasuk/get_satuan.php', // File yang akan memproses data
         data: 'tamp=' + tamp, // Data yang akan dikirim ke file pemroses
         success: function(data) { // Jika berhasil
              $('.tampung1').html(data); // Berikan hasil ke id kota
            }
           
     
    });
});
});
</script> 

<script type="text/javascript">
    jQuery(document).ready(function($){
        $(function(){
    $('#Myform1').submit(function() {
        $.ajax({
            type: 'POST',
            url: 'page/laporan/export_laporan_barangmasuk_excel.php',
            data: $(this).serialize(),
            success: function(data) {
             $(".tampung1").html(data);
             $('.table').DataTable();

            }
        });

        return false;
         e.preventDefault();
        });
    });
});
</script>


 <script type="text/javascript">
    jQuery(document).ready(function($){
        $(function(){
    $('#Myform2').submit(function() {
        $.ajax({
            type: 'POST',
            url: 'page/laporan/export_laporan_barangkeluar_excel.php',
            data: $(this).serialize(),
            success: function(data) {
             $(".tampung2").html(data);
             $('.table').DataTable();

            }
        });

        return false;
         e.preventDefault();
        });
    });
});
</script>

  


  

</body>

</html>
