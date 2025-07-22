<?php
require('../../vendor/fpdf/fpdf.php');

$koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$bln = $_POST['bln'];
$thn = $_POST['thn'];

// Siapkan query dan judul
if ($bln == 'all') {
    $sql = $koneksi->query("SELECT * FROM barang_masuk WHERE YEAR(tanggal) = '$thn' ORDER BY tanggal DESC");
    $judul = "Laporan Barang Masuk Tahun $thn";
    $periode = "Tahun $thn";
} else {
    $sql = $koneksi->query("SELECT * FROM barang_masuk WHERE MONTH(tanggal) = '$bln' AND YEAR(tanggal) = '$thn' ORDER BY tanggal DESC");
    $bulan_indonesia = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    );
    $nama_bulan = $bulan_indonesia[(int)$bln];
    $judul = "Laporan Barang Masuk Bulan $nama_bulan Tahun $thn";
    $periode = "$nama_bulan $thn";
}

// Hitung total data dan jumlah barang
$total_data = 0;
$total_jumlah = 0;
$data_array = array();

while ($row = $sql->fetch_assoc()) {
    $data_array[] = $row;
    $total_data++;
    $total_jumlah += $row['jumlah'];
}

// Custom PDF Class
class PDF extends FPDF {
    private $periode;
    private $total_data;
    private $total_jumlah;
    
    function __construct($periode, $total_data, $total_jumlah) {
        parent::__construct('L', 'mm', 'A4');
        $this->periode = $periode;
        $this->total_data = $total_data;
        $this->total_jumlah = $total_jumlah;
    }
    
    // Header
    function Header() {
        // Logo placeholder (uncomment jika ada logo)
        // $this->Image('logo.png', 10, 6, 30);
        
        // Kotak header dengan background gradient effect
        $this->SetFillColor(41, 128, 185); // Biru
        $this->Rect(10, 10, 277, 40, 'F');
        
        // Border header
        $this->SetDrawColor(31, 97, 141);
        $this->SetLineWidth(0.8);
        $this->Rect(10, 10, 277, 40);
        
        // Judul utama
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 20);
        $this->SetXY(10, 18);
        $this->Cell(277, 8, 'SISTEM STOKIS MANAGEMENT', 0, 1, 'C');
        
        // Sub judul
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(10, 28);
        $this->Cell(277, 8, 'LAPORAN BARANG MASUK', 0, 1, 'C');
        
        // Periode dengan style
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(10, 38);
        $this->Cell(277, 6, 'Periode: ' . $this->periode, 0, 1, 'C');
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
        $this->Ln(10);
    }
    
    // Footer
    function Footer() {
        // Garis atas footer
        $this->SetY(-28);
        $this->SetDrawColor(41, 128, 185);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY(), 287, $this->GetY());
        
        // Background footer
        $this->SetY(-26);
        $this->SetFillColor(245, 248, 250);
        $this->Rect(10, $this->GetY(), 277, 18, 'F');
        
        // Info export dengan layout yang lebih rapi
        $this->SetY(-22);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(52, 73, 94);
        
        // Timestamp export (kiri)
        $this->SetX(15);
        $this->Cell(80, 4, 'Diekspor: ' . date('d F Y, H:i:s'), 0, 0, 'L');
        
        // Halaman (tengah)
        $this->Cell(117, 4, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
        
        // Total info (kanan)
        $this->Cell(65, 4, 'Total: ' . number_format($this->total_data) . ' transaksi', 0, 1, 'R');
        
        // Baris kedua footer
        $this->SetY(-16);
        $this->SetX(15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(108, 117, 125);
        $this->Cell(80, 4, 'Sistem Stokis DKRIUK', 0, 0, 'L');
        
        $this->Cell(117, 4, 'Laporan dibuat secara otomatis', 0, 0, 'C');
        
        $this->Cell(65, 4, number_format($this->total_jumlah) . ' total item', 0, 0, 'R');
    }
    
    // Info box sebelum tabel dengan design yang lebih menarik
    function InfoBox($total_data, $total_jumlah) {
        // Background info dengan gradient effect
        $this->SetFillColor(236, 240, 241);
        $this->Rect(10, $this->GetY(), 277, 25, 'F');
        
        // Border info
        $this->SetDrawColor(149, 165, 166);
        $this->SetLineWidth(0.5);
        $this->Rect(10, $this->GetY(), 277, 25);
        
        $y_start = $this->GetY();
        
        // Statistik dengan design card
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(52, 73, 94);
        
        // Card 1 - Total Transaksi
        $this->SetFillColor(231, 76, 60);
        $this->Rect(25, $y_start + 5, 70, 15, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(30, $y_start + 8);
        $this->Cell(60, 4, 'Total Transaksi', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(30, $y_start + 13);
        $this->Cell(60, 5, number_format($total_data), 0, 0, 'C');
        
        // Card 2 - Total Item
        $this->SetFillColor(39, 174, 96);
        $this->Rect(105, $y_start + 5, 70, 15, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(110, $y_start + 8);
        $this->Cell(60, 4, 'Total Barang Masuk', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(110, $y_start + 13);
        $this->Cell(60, 5, number_format($total_jumlah) . ' unit', 0, 0, 'C');
        
        // Card 3 - Info Tanggal
        $this->SetFillColor(155, 89, 182);
        $this->Rect(185, $y_start + 5, 90, 15, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(190, $y_start + 8);
        $this->Cell(80, 4, 'Laporan Dibuat', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(190, $y_start + 13);
        $this->Cell(80, 5, date('d F Y'), 0, 0, 'C');
        
        $this->SetXY(10, $y_start + 30);
        $this->SetTextColor(0, 0, 0);
    }
}

// Buat instance PDF
$pdf = new PDF($periode, $total_data, $total_jumlah);
$pdf->AliasNbPages();
$pdf->AddPage();

// Info box
$pdf->InfoBox($total_data, $total_jumlah);

$pdf->Ln(5);

// Header Tabel dengan styling yang lebih profesional
$pdf->SetFont('Arial', 'B', 11);

// Background header tabel dengan gradient
$pdf->SetFillColor(52, 152, 219); // Biru header
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(41, 128, 185);
$pdf->SetLineWidth(0.5);

// Header columns dengan lebar yang disesuaikan (tanpa pengirim)
$pdf->Cell(20, 12, 'No', 1, 0, 'C', true);
$pdf->Cell(40, 12, 'ID Transaksi', 1, 0, 'C', true);
$pdf->Cell(35, 12, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(40, 12, 'Kode Barang', 1, 0, 'C', true);
$pdf->Cell(100, 12, 'Nama Barang', 1, 0, 'C', true);
$pdf->Cell(25, 12, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(17, 12, 'Satuan', 1, 1, 'C', true);

// Data Tabel dengan alternating colors
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$no = 1;
$fill = false;

foreach ($data_array as $data) {
    // Cek jika perlu page baru
    if ($pdf->GetY() > 180) {
        $pdf->AddPage();
        
        // Ulangi header tabel
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(52, 152, 219);
        $pdf->SetTextColor(255, 255, 255);
        
        $pdf->Cell(20, 12, 'No', 1, 0, 'C', true);
        $pdf->Cell(40, 12, 'ID Transaksi', 1, 0, 'C', true);
        $pdf->Cell(35, 12, 'Tanggal', 1, 0, 'C', true);
        $pdf->Cell(40, 12, 'Kode Barang', 1, 0, 'C', true);
        $pdf->Cell(100, 12, 'Nama Barang', 1, 0, 'C', true);
        $pdf->Cell(25, 12, 'Jumlah', 1, 0, 'C', true);
        $pdf->Cell(17, 12, 'Satuan', 1, 1, 'C', true);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
    }
    
    // Alternating row colors dengan warna yang lebih soft
    if ($fill) {
        $pdf->SetFillColor(248, 249, 250); // Abu-abu sangat muda
    } else {
        $pdf->SetFillColor(255, 255, 255); // Putih
    }
    
    // Format tanggal Indonesia
    $bulan_nama = array(
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
        '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
    );
    $tanggal_parts = explode('-', $data['tanggal']);
    $tanggal_formatted = $tanggal_parts[2] . ' ' . $bulan_nama[$tanggal_parts[1]] . ' ' . $tanggal_parts[0];
    
    // Potong nama barang jika terlalu panjang dengan elipsis
    $nama_barang = strlen($data['nama_barang']) > 45 ? 
                   substr($data['nama_barang'], 0, 42) . '...' : 
                   $data['nama_barang'];
    
    // Baris data
    $pdf->Cell(20, 10, $no++, 1, 0, 'C', $fill);
    $pdf->Cell(40, 10, $data['id_transaksi'], 1, 0, 'C', $fill);
    $pdf->Cell(35, 10, $tanggal_formatted, 1, 0, 'C', $fill);
    $pdf->Cell(40, 10, $data['kode_barang'], 1, 0, 'C', $fill);
    $pdf->Cell(100, 10, $nama_barang, 1, 0, 'L', $fill);
    $pdf->Cell(25, 10, number_format($data['jumlah']), 1, 0, 'R', $fill);
    $pdf->Cell(17, 10, 'pcs', 1, 1, 'C', $fill);
    
    $fill = !$fill;
}

// Summary footer tabel dengan style yang lebih menarik
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(46, 204, 113); // Hijau untuk summary
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(39, 174, 96);

$pdf->Cell(235, 12, 'TOTAL KESELURUHAN BARANG MASUK', 1, 0, 'C', true);
$pdf->Cell(25, 12, number_format($total_jumlah), 1, 0, 'R', true);
$pdf->Cell(17, 12, 'pcs', 1, 1, 'C', true);

// Tambahan informasi di bawah tabel
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(108, 117, 125);
$pdf->Cell(277, 5, 'Catatan: Laporan ini menampilkan seluruh data barang masuk pada periode ' . $periode, 0, 1, 'L');
$pdf->Cell(277, 5, 'dengan total ' . number_format($total_data) . ' transaksi dan ' . number_format($total_jumlah) . ' unit barang.', 0, 1, 'L');

// Generate filename dengan timestamp
$timestamp = date('YmdHis');
$filename = 'Laporan_Barang_Masuk_' . ($bln == 'all' ? 'Tahun_'.$thn : 'Bulan_'.$bln.'_'.$thn) . '_' . $timestamp . '.pdf';

// Output PDF
$pdf->Output('D', $filename);

// Close database connection
$koneksi->close();
exit;
?>