<?php
require('../../vendor/fpdf/fpdf.php');

class RequestBarangPDF extends FPDF
{
    private $po_number;
    private $timestamp;
    private $report_title;
    
    public function __construct($bln = '', $thn = '')
    {
        parent::__construct();
        $this->po_number = 'PO' . date('Ymd') . rand(1000, 9999);
        $this->timestamp = date('d-m-Y H:i:s');
        
        // Set report title berdasarkan filter
        if ($bln == 'all' || empty($bln)) {
            $this->report_title = "Laporan Request Barang Tahun $thn";
        } else {
            $month_names = [
                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
                '4' => 'April', '5' => 'Mei', '6' => 'Juni',
                '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $month_name = $month_names[$bln] ?? "Bulan $bln";
            $this->report_title = "Laporan Request Barang $month_name $thn";
        }
    }
    
    function Header()
    {
        // Background header yang subtle
        $this->SetFillColor(248, 249, 250);
        $this->Rect(0, 0, 210, 60, 'F');
        
        // Logo
        if (file_exists('../../img/dkriuklogo.png')) {
            $this->Image('../../img/dkriuklogo.png', 15, 15, 35, 25);
        } else {
            // Placeholder logo jika file tidak ada
            $this->SetFillColor(220, 53, 69);
            $this->SetTextColor(255, 255, 255);
            $this->Rect(15, 15, 35, 25, 'F');
            $this->SetFont('Arial', 'B', 10);
            $this->SetXY(20, 25);
            $this->Cell(25, 6, "D'KRIUK", 0, 0, 'C');
            $this->SetTextColor(0, 0, 0);
        }
        
        // Header perusahaan
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(55, 18);
        $this->SetTextColor(220, 53, 69);
        $this->Cell(0, 8, "LAPORAN PERMINTAAN BARANG D'KRIUK", 0, 1);
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(55, 26);
        $this->SetTextColor(108, 117, 125);
        $this->Cell(0, 6, "FRIED CHICKEN CABANG BABELAN BEKASI", 0, 1);
        
        // Garis pemisah
        $this->SetDrawColor(220, 53, 69);
        $this->SetLineWidth(0.8);
        $this->Line(55, 35, 195, 35);
        
        // Info perusahaan
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(33, 37, 41);
        $this->SetXY(15, 45);
        $this->Cell(0, 5, "PT. RAJA RASA KULINER", 0, 1);
        
        // PO dan Timestamp dalam box
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(233, 236, 239);
        
        $this->SetXY(15, 52);
        $this->Cell(50, 6, " No PO: " . $this->po_number, 1, 0, 'L', true);
        
        $this->SetXY(140, 52);
        $this->Cell(55, 6, " Timestamp: " . $this->timestamp, 1, 1, 'L', true);
        
        $this->Ln(8);
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);
    }
    
    function Footer()
    {
        $this->SetY(-20);
        
        // Garis atas footer
        $this->SetDrawColor(220, 220, 220);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        
        $this->Ln(3);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(108, 117, 125);
        
        // Info halaman
        $this->Cell(0, 5, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'C');
        
        // Tanggal cetak
        $this->SetY(-10);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, 'Dicetak pada: ' . date('d F Y, H:i') . ' WIB', 0, 0, 'R');
    }
    
    function ReportTitle()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(33, 37, 41);
        
        // Background title
        $this->SetFillColor(248, 249, 250);
        $this->Cell(0, 10, $this->report_title, 0, 1, 'C', true);
        $this->Ln(5);
    }
    
    function TableHeader()
    {
        // Header tabel dengan gradient effect
        $this->SetFillColor(52, 58, 64);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);
        
        $header = [
            ['text' => 'No', 'width' => 15],
            ['text' => 'ID Request', 'width' => 25],
            ['text' => 'Tanggal', 'width' => 25],
            ['text' => 'Kode Barang', 'width' => 25],
            ['text' => 'Nama Barang', 'width' => 60],
            ['text' => 'Pengirim', 'width' => 25],
            ['text' => 'Jumlah', 'width' => 15]
        ];
        
        foreach ($header as $col) {
            $this->Cell($col['width'], 10, $col['text'], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
    }
    
    function TableRow($data, $no)
    {
        $this->SetFont('Arial', '', 8);
        
        // Alternating row colors
        if ($no % 2 == 0) {
            $this->SetFillColor(248, 249, 250);
        } else {
            $this->SetFillColor(255, 255, 255);
        }
        
        // Hitung tinggi baris berdasarkan text terpanjang
        $max_lines = max(
            $this->NbLines(60, $data['nama_barang'] ?? ''),
            $this->NbLines(25, $data['pengirim'] ?? ''),
            1
        );
        $row_height = max(8, $max_lines * 4);
        
        $y_start = $this->GetY();
        
        // No
        $this->Cell(15, $row_height, $no, 1, 0, 'C', true);
        
        // ID Request
        $this->Cell(25, $row_height, $data['id_request'] ?? '', 1, 0, 'C', true);
        
        // Tanggal (format yang lebih rapi)
        $tanggal = isset($data['tanggal']) ? date('d/m/Y', strtotime($data['tanggal'])) : '';
        $this->Cell(25, $row_height, $tanggal, 1, 0, 'C', true);
        
        // Kode Barang
        $this->Cell(25, $row_height, $data['kode_barang'] ?? '', 1, 0, 'C', true);
        
        // Nama Barang (dengan word wrap)
        $x_before = $this->GetX();
        $y_before = $this->GetY();
        $this->MultiCell(60, 4, $data['nama_barang'] ?? '', 1, 'L', true);
        $this->SetXY($x_before + 60, $y_before);
        
        // Pengirim
        $this->Cell(25, $row_height, $data['pengirim'] ?? '', 1, 0, 'C', true);
        
        // Jumlah
        $this->Cell(15, $row_height, $data['jumlah'] ?? '', 1, 1, 'C', true);
    }
    
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else $i++;
        }
        return $nl;
    }
    
    function Summary($total_records, $total_items)
    {
        $this->Ln(10);
        
        // Box summary
        $this->SetFillColor(233, 236, 239);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 8, 'RINGKASAN LAPORAN', 0, 1, 'C', true);
        
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(248, 249, 250);
        $this->Cell(95, 6, ' Total Transaksi Request: ' . number_format($total_records), 1, 0, 'L', true);
        $this->Cell(95, 6, ' Total Item Direquest: ' . number_format($total_items), 1, 1, 'L', true);
    }
    
    function SignatureArea()
    {
        $this->Ln(15);
        $this->SetFont('Arial', '', 9);
        
        // Kotak tanda tangan
        $this->Cell(0, 5, 'Bekasi, ' . date('d F Y'), 0, 1, 'R');
        $this->Ln(5);
        
        $this->Cell(140, 5, '', 0, 0);
        $this->Cell(50, 5, 'Mengetahui,', 0, 1, 'C');
        $this->Cell(140, 5, '', 0, 0);
        $this->Cell(50, 5, 'Manager Operasional', 0, 1, 'C');
        
        $this->Ln(15);
        $this->Cell(140, 5, '', 0, 0);
        $this->Cell(50, 1, '', 'T', 1, 'C');
        $this->Cell(140, 5, '', 0, 0);
        $this->Cell(50, 5, '( Nama & Paraf )', 0, 1, 'C');
    }
}

// Main execution
try {
    // Cek apakah data POST tersedia
    if (!isset($_POST['bln']) || !isset($_POST['thn'])) {
        throw new Exception("Parameter bulan dan tahun harus diisi!");
    }
    
    $bln = $_POST['bln'];
    $thn = $_POST['thn'];
    
    // Validasi input
    if (!is_numeric($thn) || $thn < 2020 || $thn > date('Y') + 1) {
        throw new Exception("Tahun tidak valid!");
    }
    
    if ($bln !== 'all' && (!is_numeric($bln) || $bln < 1 || $bln > 12)) {
        throw new Exception("Bulan tidak valid!");
    }
    
    // Database connection dengan error handling
    $koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");
    
    if ($koneksi->connect_error) {
        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
    }
    
    // Query dengan prepared statement untuk keamanan
    if ($bln == 'all') {
        $stmt = $koneksi->prepare("SELECT * FROM request_barang WHERE YEAR(tanggal) = ? ORDER BY tanggal DESC");
        $stmt->bind_param("s", $thn);
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM request_barang WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ? ORDER BY tanggal DESC");
        $stmt->bind_param("ss", $bln, $thn);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Bersihkan buffer output
    ob_clean();
    
    // Buat PDF
    $pdf = new RequestBarangPDF($bln, $thn);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    // Judul laporan
    $pdf->ReportTitle();
    
    // Header tabel
    $pdf->TableHeader();
    
    // Data tabel
    $no = 1;
    $total_items = 0;
    $total_records = 0;
    
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            // Cek apakah perlu halaman baru
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->TableHeader();
            }
            
            $pdf->TableRow($data, $no);
            $no++;
            $total_items += (int)($data['jumlah'] ?? 0);
            $total_records++;
        }
        
        // Summary
        $pdf->Summary($total_records, $total_items);
        
    } else {
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 20, 'Tidak ada data untuk periode yang dipilih', 0, 1, 'C');
    }
    
    // Area tanda tangan
    $pdf->SignatureArea();
    
    // Output PDF
    $filename = 'Laporan_Request_Barang_' . date('d-m-Y_H-i-s') . '.pdf';
    $pdf->Output('D', $filename);
    
    $stmt->close();
    $koneksi->close();
    
} catch (Exception $e) {
    // Error handling
    ob_clean();
    http_response_code(500);
    echo "
    <html>
    <head><title>Error</title></head>
    <body style='font-family: Arial; padding: 50px; text-align: center;'>
        <h2 style='color: #dc3545;'>❌ Terjadi Kesalahan</h2>
        <p style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>
            " . htmlspecialchars($e->getMessage()) . "
        </p>
        <button onclick='history.back()' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>
            ← Kembali
        </button>
    </body>
    </html>";
    exit;
}
?>