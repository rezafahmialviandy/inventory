<?php
// Koneksi Database
$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Default values
$bln = $_POST['bln'] ?? 'all';
$thn = $_POST['thn'] ?? date('Y');
$filter_type = $_POST['filter_type'] ?? 'monthly'; // monthly, yearly, date_range
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

// Ambil range tahun
$sql_tahun = $koneksi->query("SELECT MIN(YEAR(tanggal)) as tahun_awal, MAX(YEAR(tanggal)) as tahun_akhir FROM request_barang");
$row_tahun = $sql_tahun->fetch_assoc();
$tahun_awal = $row_tahun['tahun_awal'] ?? date('Y');
$tahun_akhir = $row_tahun['tahun_akhir'] ?? date('Y');

// Build query berdasarkan filter type
$query = "SELECT * FROM request_barang WHERE 1=1";
$filter_description = "";

if ($filter_type === 'date_range' && !empty($start_date) && !empty($end_date)) {
    // Filter berdasarkan range tanggal
    $query .= " AND DATE(tanggal) BETWEEN '$start_date' AND '$end_date'";
    $filter_description = "Periode: " . date('d/m/Y', strtotime($start_date)) . " - " . date('d/m/Y', strtotime($end_date));
} elseif ($filter_type === 'yearly') {
    // Filter berdasarkan tahun saja
    $query .= " AND YEAR(tanggal) = '$thn'";
    $filter_description = "Tahun: $thn";
} else {
    // Filter berdasarkan bulan dan tahun (default)
    $query .= " AND YEAR(tanggal) = '$thn'";
    if ($bln != 'all') {
        $query .= " AND MONTH(tanggal) = '$bln'";
        $bulan_names = [
            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
        ];
        $filter_description = "Bulan: " . $bulan_names[$bln] . " $thn";
    } else {
        $filter_description = "Tahun: $thn (Semua Bulan)";
    }
}

$query .= " ORDER BY tanggal DESC";
$sql = $koneksi->query($query);

// Daftar bulan
$bulan_arr = [
    1 => "January", 2 => "February", 3 => "March", 4 => "April",
    5 => "May", 6 => "June", 7 => "July", 8 => "August",
    9 => "September", 10 => "October", 11 => "November", 12 => "December"
];
?>

<!-- Form untuk Filter dan Export -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Request Barang</h6>
        </div>
        <div class="card-body">
            <!-- Filter Type Selection -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="Filter Type">
                        <input type="radio" class="btn-check" name="filter_type" id="monthly" value="monthly" <?= ($filter_type == 'monthly') ? 'checked' : ''; ?> onchange="toggleFilterType()">
                        <label class="btn btn-outline-primary" for="monthly">Filter Bulanan</label>
                        
                        <input type="radio" class="btn-check" name="filter_type" id="yearly" value="yearly" <?= ($filter_type == 'yearly') ? 'checked' : ''; ?> onchange="toggleFilterType()">
                        <label class="btn btn-outline-primary" for="yearly">Filter Tahunan</label>
                        
                        <input type="radio" class="btn-check" name="filter_type" id="date_range" value="date_range" <?= ($filter_type == 'date_range') ? 'checked' : ''; ?> onchange="toggleFilterType()">
                        <label class="btn btn-outline-primary" for="date_range">Filter Tanggal</label>
                    </div>
                </div>
            </div>

            <form method="post" action="" id="filterForm">
                <input type="hidden" name="filter_type" id="filter_type_input" value="<?= $filter_type ?>">
                
                <!-- Monthly Filter -->
                <div class="row form-group" id="monthly_filter" style="<?= ($filter_type != 'monthly') ? 'display: none;' : ''; ?>">
                    <div class="col-md-4">
                        <label for="bln">Pilih Bulan:</label>
                        <select class="form-control" name="bln" onchange="submitFilter();">
                            <option value="all" <?= ($bln == 'all') ? 'selected' : ''; ?>>Semua Bulan</option>
                            <?php
                            foreach ($bulan_arr as $key => $val) {
                                echo "<option value='$key' " . ($bln == $key ? 'selected' : '') . ">$val</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="thn">Pilih Tahun:</label>
                        <select name="thn" class="form-control" onchange="submitFilter();">
                            <?php
                            for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
                                $selected = ($a == $thn) ? "selected" : "";
                                echo "<option value='$a' $selected>$a</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary mr-2" onclick="submitFilter();">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="submit" class="btn btn-danger"
                            formaction="page/laporan/export_laporan_requestbarang_pdf.php"
                            formtarget="_blank">
                            <i class="fas fa-file-pdf"></i> Export to PDF
                        </button>
                    </div>
                </div>

                <!-- Yearly Filter -->
                <div class="row form-group" id="yearly_filter" style="<?= ($filter_type != 'yearly') ? 'display: none;' : ''; ?>">
                    <div class="col-md-3">
                        <label for="thn">Pilih Tahun:</label>
                        <select name="thn" class="form-control" onchange="submitFilter();">
                            <?php
                            for ($a = $tahun_awal; $a <= $tahun_akhir; $a++) {
                                $selected = ($a == $thn) ? "selected" : "";
                                echo "<option value='$a' $selected>$a</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary mr-2" onclick="submitFilter();">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="submit" class="btn btn-danger"
                            formaction="page/laporan/export_laporan_requestbarang_pdf.php"
                            formtarget="_blank">
                            <i class="fas fa-file-pdf"></i> Export to PDF
                        </button>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="row form-group" id="date_range_filter" style="<?= ($filter_type != 'date_range') ? 'display: none;' : ''; ?>">
                    <div class="col-md-3">
                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" 
                               value="<?= $start_date ?>" onchange="validateDateRange();">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">Tanggal Selesai:</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" 
                               value="<?= $end_date ?>" onchange="validateDateRange();">
                    </div>
                    <div class="col-md-6">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary mr-2" onclick="submitFilter();">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="submit" class="btn btn-danger"
                            formaction="page/laporan/export_laporan_requestbarang_pdf.php"
                            formtarget="_blank">
                            <i class="fas fa-file-pdf"></i> Export to PDF
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="setDateRangePresets('today');">
                            Hari Ini
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="setDateRangePresets('week');">
                            Minggu Ini
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="setDateRangePresets('month');">
                            Bulan Ini
                        </button>
                    </div>
                </div>
            </form>

            <!-- Filter Information -->
            <?php if (!empty($filter_description)): ?>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i> <strong>Filter Aktif:</strong> <?= $filter_description ?>
                <?php if ($sql->num_rows > 0): ?>
                    | <strong>Ditemukan:</strong> <?= $sql->num_rows ?> record
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- TABEL HASIL -->
            <div class="tampung1 mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Id Request</th>
                                <th>Tanggal Request</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Jumlah Request</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_jumlah = 0;
                            if ($sql->num_rows > 0) {
                                while ($data = $sql->fetch_assoc()) {
                                    $total_jumlah += intval($data['jumlah']);
                                    echo "<tr>
                                        <td>$no</td>
                                        <td><span class='badge badge-primary'>{$data['id_request']}</span></td>
                                        <td>" . date('d/m/Y', strtotime($data['tanggal'])) . "</td>
                                        <td><strong>{$data['kode_barang']}</strong></td>
                                        <td>{$data['nama_barang']}</td>
                                        <td>{$data['supplier']}</td>
                                        <td class='text-right'><strong>" . number_format($data['jumlah']) . "</strong></td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted'>
                                        <i class='fas fa-inbox fa-3x mb-3'></i><br>
                                        Tidak ada data ditemukan untuk filter yang dipilih.
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                        <?php if ($sql->num_rows > 0): ?>
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="6" class="text-right">Total Jumlah Request:</th>
                                <th class="text-right"><?= number_format($total_jumlah) ?></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilterType() {
    const filterType = document.querySelector('input[name="filter_type"]:checked').value;
    document.getElementById('filter_type_input').value = filterType;
    
    // Hide all filter sections
    document.getElementById('monthly_filter').style.display = 'none';
    document.getElementById('yearly_filter').style.display = 'none';
    document.getElementById('date_range_filter').style.display = 'none';
    
    // Show selected filter section
    document.getElementById(filterType + '_filter').style.display = 'block';
}

function submitFilter() {
    document.getElementById('filterForm').submit();
}

function validateDateRange() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate && startDate > endDate) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai!');
        document.getElementById('end_date').value = startDate;
    }
}

function setDateRangePresets(preset) {
    const today = new Date();
    let startDate, endDate;
    
    switch (preset) {
        case 'today':
            startDate = endDate = today.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            startDate = weekStart.toISOString().split('T')[0];
            endDate = weekEnd.toISOString().split('T')[0];
            break;
        case 'month':
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startDate = monthStart.toISOString().split('T')[0];
            endDate = monthEnd.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('start_date').value = startDate;
    document.getElementById('end_date').value = endDate;
}

// Initialize filter type on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFilterType();
});
</script>

<style>
.btn-check {
    position: absolute;
    clip: rect(0, 0, 0, 0);
    pointer-events: none;
}

.btn-check:checked + .btn {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.badge-primary {
    background-color: #007bff;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 123, 255, 0.05);
}

.thead-dark th {
    background-color: #343a40;
    border-color: #454d55;
}

.thead-light th {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.fa-inbox {
    color: #6c757d;
}
</style>