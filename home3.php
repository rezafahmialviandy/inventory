	<link rel="icon" type="image/x-icon" href="img/dkriuk.jpg">
<br>

       <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
           
          </div>
			<marquee><h2>STOKIS D'kriuk Fried Chicken
      </marquee></h2>
		  <br></br>
          <!-- Content Row -->
          <div class="row">

			
			
			  <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                     <a  href="?page=supplier"> <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><h4>Data Supplier</h4></div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                        
                        </div>
                        <div class="col">
                         
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-black-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
			
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                     <a  href="?page=gudang"> <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><h4>Data Gudang</h4></div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                        
                        </div>
                        <div class="col">
                         
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-black-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

			
			 <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <a  href="?page=barangmasuk"> <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><h4>Barang Masuk</h4></div></a>
                   
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-black-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                     <a  href="?page=barangkeluar"> <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><h4>Barang Keluar</h4></div></a>
           
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-black-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
			
		
		
			
			
          </div>

<?php
// --- Koneksi DB (asumsi $koneksi sudah ada) --- //
// Ambil tahun-tahun unik dari data Ayam
$tahunList = [];
$sqlTahun = $koneksi->query("SELECT DISTINCT YEAR(tanggal) as tahun FROM barang_masuk WHERE kode_barang = 'BAR-0725001' ORDER BY tahun DESC");
while ($row = $sqlTahun->fetch_assoc()) {
    $tahunList[] = $row['tahun'];
}
$tahunDefault = count($tahunList) > 0 ? $tahunList[0] : date('Y');
$tahunDipilih = isset($_GET['tahun_ayam']) ? $_GET['tahun_ayam'] : $tahunDefault;

// --- Data bulanan Ayam untuk tahun terpilih --- //
$bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$jumlahBulanan = array_fill(1,12,0); // index 1-12

$sql = $koneksi->query("SELECT MONTH(tanggal) as bulan, SUM(jumlah) as total FROM barang_masuk WHERE kode_barang = 'BAR-0725001' AND YEAR(tanggal) = '$tahunDipilih' GROUP BY MONTH(tanggal)");
while ($row = $sql->fetch_assoc()) {
    $jumlahBulanan[$row['bulan']] = (int)$row['total'];
}
$D = array_sum($jumlahBulanan);

// --- Parameter EOQ --- //
$S = isset($_GET['S']) ? (int)$_GET['S'] : 45000;
$H = isset($_GET['H']) ? (int)$_GET['H'] : 100000;
$EOQ = $D > 0 ? sqrt(2 * $D * $S / $H) : 0;
?>

<!-- ECharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<div class="row">
  <div class="col-12 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <!-- Form Pilih Tahun & Parameter EOQ -->
        <form id="formEOQ" method="GET" class="mb-3 d-flex align-items-end flex-wrap" style="gap:10px">
            <div>
              <label for="tahun_ayam"><b>Tahun:</b></label>
              <select name="tahun_ayam" id="tahun_ayam" class="form-control" style="width:auto;display:inline-block">
                  <?php foreach($tahunList as $tahun) { ?>
                      <option value="<?php echo $tahun; ?>" <?php if($tahun==$tahunDipilih) echo "selected"; ?>><?php echo $tahun; ?></option>
                  <?php } ?>
              </select>
            </div>
            <div>
              <label>Biaya Pesan/S (Rp):</label>
              <input type="number" name="S" value="<?php echo $S; ?>" class="form-control" style="width:120px" min="1" required>
            </div>
            <div>
              <label>Biaya Simpan/H (Rp):</label>
              <input type="number" name="H" value="<?php echo $H; ?>" class="form-control" style="width:120px" min="1" required>
            </div>
            <button class="btn btn-primary" type="submit" style="height:40px;margin-top:24px;">Tampilkan</button>
        </form>

        <!-- Grafik Bar Chart Bulanan -->
        <div id="barChartAyam" style="height:400px;"></div>
        <script>
        var bulanLabels = <?php echo json_encode($bulanLabels); ?>;
        var jumlahBulanan = <?php echo json_encode(array_values($jumlahBulanan)); ?>;

        var barChart = echarts.init(document.getElementById('barChartAyam'));
        var option = {
            title: {
                text: 'Jumlah Ayam Masuk per Bulan (<?php echo $tahunDipilih; ?>)',
                left: 'center'
            },
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: bulanLabels,
                name: 'Bulan'
            },
            yAxis: {
                type: 'value',
                name: 'Jumlah Ayam'
            },
            series: [{
                data: jumlahBulanan,
                type: 'bar',
                barWidth: '50%',
                itemStyle: {
                    color: '#4e73df'
                }
            }]
        };
        barChart.setOption(option);
        </script>

        <!-- Parameter EOQ -->
        <div style="margin-top:25px">

<!-- Container horizontal -->
<div class="row mt-4" style="background:#f6f6ff;padding:16px;border-radius:10px;">
  <!-- Alur langkah -->
  <div class="col-md-6 mb-2">
    <strong>Alur Perhitungan EOQ:</strong>
    <ol style="margin-top:10px;">
      <li><b>Tentukan total kebutuhan/pembelian Ayam per tahun (D).</b><br>
        <small>Contoh: Total ayam masuk selama tahun <b><?php echo $tahunDipilih; ?></b> = <b><?php echo $D; ?></b> pcs</small>
      </li>
      <li><b>Tentukan biaya pemesanan tiap kali order (S).</b><br>
        <small>Misal biaya pesan = <b>Rp<?php echo number_format($S,0,',','.'); ?></b> per pemesanan</small>
      </li>
      <li><b>Tentukan biaya simpan per unit per tahun (H).</b><br>
        <small>Misal biaya simpan = <b>Rp<?php echo number_format($H,0,',','.'); ?></b> per pcs per tahun</small>
      </li>
      <li><b>Hitung EOQ dengan rumus berikut:</b></li>
    </ol>
  </div>
  <!-- Rumus EOQ -->
  <div class="col-md-6 mb-2" style="border-left:1px solid #eee;">
    <strong>Rumus EOQ:</strong><br>
    <div style="font-size:1.3em; margin:10px 0 10px 0;">
      EOQ = <span style="font-family:serif;">√(2 × D × S / H)</span>
    </div>
    <small>
      <b>Di mana:</b><br>
      D = Permintaan per tahun<br>
      S = Biaya pesan per order<br>
      H = Biaya simpan per unit per tahun
    </small>
    <div style="margin:15px 0 0 0;">
      <b>Hasil EOQ:</b>
      <br>
      EOQ = √(2 × <?php echo $D; ?> × <?php echo $S; ?> / <?php echo $H; ?>)
      <br>
      = <b style="color:#2e59d9;"><?php echo round($EOQ, 2); ?></b> pcs
    </div>
  </div>
</div>

            <center>
            <strong>Parameter EOQ Tahun <?php echo $tahunDipilih; ?>:</strong><br>
            Permintaan/Tahun (D): <?php echo $D; ?> pcs<br>
            Biaya Pesan/Pesanan (S): Rp<?php echo number_format($S,0,',','.'); ?><br>
            Biaya Simpan/Unit/Tahun (H): Rp<?php echo number_format($H,0,',','.'); ?><br>
            <span style="color:red">
                Nilai EOQ Optimal: <b><?php echo round($EOQ); ?></b> pcs
            </span>

            </center>
        </div>
      </div>
    </div>
  </div>
</div>

