<?php
// Koneksi sudah ada di atas
$year = date('Y');
$sql = $koneksi->query("SELECT SUM(jumlah) as total FROM barang_masuk WHERE kode_barang = 'BAR-0725001' AND YEAR(tanggal) = '$year'");
$data = $sql->fetch_assoc();
$permintaan_tahun = (int)$data['total']; // D
if($permintaan_tahun == 0) $permintaan_tahun = 1; // Biar tidak error jika belum ada transaksi

// Parameter EOQ
$S = 45000;    // biaya pesan per pesanan
$H = 100000;   // biaya simpan per unit per tahun

?>
<!-- EOQ CHART -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="m-0 font-weight-bold text-primary">Grafik EOQ Ayam (BAR-0725001) Tahun <?php echo $year; ?></h5>
    </div>
    <div class="card-body">
        <div id="eoqChart" style="height:400px;"></div>
        <script>
            var D = <?php echo $permintaan_tahun; ?>;
            var S = <?php echo $S; ?>;
            var H = <?php echo $H; ?>;
            var EOQ = Math.sqrt(2 * D * S / H);

            var orderSizes = [];
            var totalCosts = [];

            for (var q = 1; q <= Math.max(30, D*2); q++) {
                var TC = (D / q) * S + (q / 2) * H;
                orderSizes.push(q);
                totalCosts.push(TC);
            }

            var myChart = echarts.init(document.getElementById('eoqChart'));
            var option = {
                title: {
                    text: 'EOQ Grafik (Ayam)',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'axis',
                    formatter: function(params) {
                        var q = params[0].axisValue;
                        var cost = params[0].data;
                        return 'Order Size: ' + q + '<br/>Total Cost: Rp' + cost.toLocaleString();
                    }
                },
                xAxis: {
                    name: 'Order Size (pcs)',
                    type: 'category',
                    data: orderSizes
                },
                yAxis: {
                    name: 'Total Cost (Rp)',
                    type: 'value'
                },
                series: [{
                    data: totalCosts,
                    type: 'line',
                    smooth: true,
                    markPoint: {
                        data: [
                            {
                                name: 'EOQ',
                                value: EOQ.toFixed(2),
                                xAxis: Math.round(EOQ),
                                yAxis: ((D / EOQ) * S + (EOQ / 2) * H)
                            }
                        ],
                        symbol: 'pin',
                        symbolSize: 60,
                        label: {
                            formatter: function(param) {
                                return 'EOQ: ' + EOQ.toFixed(2);
                            },
                            color: '#fff'
                        }
                    },
                    lineStyle: { width: 3 }
                }]
            };
            myChart.setOption(option);
        </script>
        <p style="margin-top:10px">
          <strong>Parameter EOQ Tahun <?php echo $year; ?>:</strong><br>
          Permintaan/Tahun (D): <?php echo $permintaan_tahun; ?> pcs<br>
          Biaya Pesan/Pesanan (S): Rp<?php echo number_format($S,0,',','.'); ?><br>
          Biaya Simpan/Unit/Tahun (H): Rp<?php echo number_format($H,0,',','.'); ?><br>
          <span style="color:red">Nilai EOQ Optimal: <b id="eoqValue"></b> pcs</span>
        </p>
        <script>
            document.getElementById('eoqValue').innerText = EOQ.toFixed(2);
        </script>
    </div>
</div>
