<?php
require('../../vendor/fpdf/fpdf.php');

class RequestBarangPDF extends FPDF
{
    private $po_number;
    private $timestamp;
    private $report_title;
    private $company_info;
    
    public function __construct($bln = '', $thn = '')
    {
        parent::__construct();
        $this->po_number = 'PO' . date('Ymd') . rand(1000, 9999);
        $this->timestamp = date('d-m-Y H:i:s');
        
        // Company information
        $this->company_info = [
            'name' => "D'KRIUK FRIED CHICKEN",
            'subtitle' => 'CABANG BABELAN BEKASI',
            'company' => 'PT. RAJA RASA KULINER',
            'address' => 'Jl. Raya Babelan No. 123, Babelan, Bekasi',
            'phone' => 'Telp: (021) 1234-5678 | Email: info@dkriuk.com'
        ];
        
        // Set report title berdasarkan filter
        if ($bln == 'all' || empty($bln)) {
            $this->report_title = "LAPORAN PERMINTAAN BARANG TAHUN $thn";
        } else {
            $month_names = [
                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
                '4' => 'April', '5' => 'Mei', '6' => 'Juni',
                '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $month_name = $month_names[$bln] ?? "Bulan $bln";
            $this->report_title = "LAPORAN PERMINTAAN BARANG $month_name $thn";
        }
    }
    
    function Header()
    {
        // Background header dengan gradient effect
        $this->SetFillColor(245, 248, 250);
        $this->Rect(0, 0, 210, 70, 'F');
        
        // Border atas dengan warna brand
        $this->SetFillColor(220, 53, 69);
        $this->Rect(0, 0, 210, 3, 'F');
        
        // Logo atau placeholder
        if (file_exists('../../img/dkriuklogo.png')) {
            $this->Image('../../img/dkriuklogo.png', 15, 15, 30, 30);
        } else {
            // Logo placeholder dengan desain yang lebih menarik
            $this->SetFillColor(220, 53, 69);
            $this->RoundedRect(15, 15, 30, 30, 3, 'F');
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 12);
            $this->SetXY(19, 25);
            $this->Cell(22, 6, "D'KRIUK", 0, 0, 'C');
            $this->SetFont('Arial', '', 8);
            $this->SetXY(19, 32);
            $this->Cell(22, 4, "CHICKEN", 0, 0, 'C');
            $this->SetTextColor(0, 0, 0);
        }
        
        // Header company info dengan layout yang lebih rapi
        $this->SetFont('Arial', 'B', 18);
        $this->SetXY(55, 18);
        $this->SetTextColor(220, 53, 69);
        $this->Cell(0, 8, $this->company_info['name'], 0, 1);
        
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY(55, 28);
        $this->SetTextColor(108, 117, 125);
        $this->Cell(0, 6, $this->company_info['subtitle'], 0, 1);
        
        $this->SetFont('Arial', '', 9);
        $this->SetXY(55, 36);
        $this->SetTextColor(73, 80, 87);
        $this->Cell(0, 4, $this->company_info['company'], 0, 1);
        
        $this->SetFont('Arial', '', 8);
        $this->SetXY(55, 42);
        $this->Cell(0, 4, $this->company_info['address'], 0, 1);
        $this->SetXY(55, 46);
        $this->Cell(0, 4, $this->company_info['phone'], 0, 1);
        
        // Garis pemisah dengan style yang lebih elegan
        $this->SetDrawColor(220, 53, 69);
        $this->SetLineWidth(1);
        $this->Line(15, 55, 195, 55);
        
        // Info box dengan style modern
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(220, 220, 220);
        
        // PO Number box
        $this->RoundedRect(15, 60, 60, 8, 1, 'DF');
        $this->SetXY(17, 62);
        $this->SetTextColor(33, 37, 41);
        $this->Cell(0, 4, "No. PO: " . $this->po_number, 0, 0);
        
        // Timestamp box
        $this->RoundedRect(135, 60, 60, 8, 1, 'DF');
        $this->SetXY(137, 62);
        $this->Cell(0, 4, "Timestamp: " . $this->timestamp, 0, 0);
        
        $this->Ln(18);
        
        // Reset colors
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);
    }
    
    function Footer()
    {
        $this->SetY(-25);
        
        // Footer background
        $this->SetFillColor(248, 249, 250);
        $this->Rect(0, $this->GetY() - 2, 210, 25, 'F');
        
        // Garis atas footer
        $this->SetDrawColor(220, 53, 69);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        
        $this->Ln(5);
        
        // Footer content dengan layout yang lebih baik
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(108, 117, 125);
        
        // Informasi halaman di kiri
        $this->SetXY(15, $this->GetY());
        $this->Cell(60, 4, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'L');
        
        // Informasi sistem di tengah
        $this->SetX(75);
        $this->Cell(60, 4, 'Sistem Inventory D\'Kriuk v2.0', 0, 0, 'C');
        
        // Tanggal cetak di kanan
        $this->SetX(135);
        $this->Cell(60, 4, 'Dicetak: ' . date('d F Y, H:i') . ' WIB', 0, 0, 'R');
        
        // Copyright
        $this->SetXY(15, $this->GetY() + 6);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(180, 4, '© 2024 D\'Kriuk Fried Chicken - All Rights Reserved', 0, 0, 'C');
    }
    
    function ReportTitle()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(33, 37, 41);
        
        // Background title dengan border
        $this->SetFillColor(248, 249, 250);
        $this->SetDrawColor(220, 53, 69);
        $this->RoundedRect(15, $this->GetY(), 180, 12, 2, 'DF');
        
        $this->SetXY(15, $this->GetY() + 3);
        $this->Cell(180, 6, $this->report_title, 0, 1, 'C');
        $this->Ln(8);
    }
    
    function TableHeader()
    {
        // Header tabel dengan gradient modern
        $this->SetFillColor(33, 37, 41);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);
        $this->SetDrawColor(255, 255, 255);
        
        $header = [
            ['text' => 'No', 'width' => 18],
            ['text' => 'ID Request', 'width' => 28],
            ['text' => 'Tanggal', 'width' => 28],
            ['text' => 'Kode Barang', 'width' => 32],
            ['text' => 'Nama Barang', 'width' => 30],
            ['text' => 'Supplier', 'width' => 38],
            ['text' => 'Jumlah', 'width' => 18]
        ];
        
        foreach ($header as $col) {
            $this->Cell($col['width'], 12, $col['text'], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Sub-header dengan warna lebih terang
        $this->SetFillColor(52, 58, 64);
        $this->Cell(192, 2, '', 0, 1, 'C', true);
        
        // Reset color
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(220, 220, 220);
    }
    
    function TableRow($data, $no)
    {
        $this->SetFont('Arial', '', 9);
        
        // Alternating row colors yang lebih soft
        if ($no % 2 == 0) {
            $this->SetFillColor(250, 251, 252);
        } else {
            $this->SetFillColor(255, 255, 255);
        }
        
        // Hitung tinggi baris
        $max_lines = max(
            $this->NbLines(30, $data['nama_barang'] ?? ''),
            $this->NbLines(38, $data['supplier'] ?? ''),
            1
        );
        $row_height = max(8, $max_lines * 4 + 2);
        
        $y_start = $this->GetY();
        
        // No dengan styling
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(18, $row_height, $no, 1, 0, 'C', true);
        $this->SetFont('Arial', '', 9);
        
        // ID Request
        $this->Cell(28, $row_height, $data['id_request'] ?? '', 1, 0, 'C', true);
        
        // Tanggal dengan format yang lebih baik
        $tanggal = '';
        if (isset($data['tanggal']) && $data['tanggal'] != '') {
            $date = new DateTime($data['tanggal']);
            $tanggal = $date->format('d/m/Y');
        }
        $this->Cell(28, $row_height, $tanggal, 1, 0, 'C', true);
        
        // Kode Barang
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(32, $row_height, $data['kode_barang'] ?? '', 1, 0, 'C', true);
        $this->SetFont('Arial', '', 9);
        
        // Nama Barang dengan word wrap
        $x_before = $this->GetX();
        $y_before = $this->GetY();
        $this->MultiCell(30, 8, $data['nama_barang'] ?? '', 1, 'L', true);
        $this->SetXY($x_before + 30, $y_before);
        
        // Supplier dengan word wrap
        $x_before = $this->GetX();
        $y_before = $this->GetY();
        $this->MultiCell(38, 8, $data['supplier'] ?? '', 1, 'C', true);
        $this->SetXY($x_before + 38, $y_before);
        
        // Jumlah dengan format number
        $jumlah = isset($data['jumlah']) ? number_format($data['jumlah']) : '';
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(18, $row_height, $jumlah, 1, 1, 'C', true);
        $this->SetFont('Arial', '', 9);
    }
    
    function Summary($total_records, $total_items)
    {
        $this->Ln(8);
        
        // Summary box dengan design yang lebih menarik
        $this->SetFillColor(220, 53, 69);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'RINGKASAN LAPORAN', 0, 1, 'C', true);
        
        // Summary content
        $this->SetFillColor(248, 249, 250);
        $this->SetTextColor(33, 37, 41);
        $this->SetFont('Arial', '', 10);
        
        $this->Cell(95, 8, '  Total Transaksi Request: ' . number_format($total_records) . ' transaksi', 1, 0, 'L', true);
        $this->Cell(95, 8, '  Total Item yang Direquest: ' . number_format($total_items) . ' item', 1, 1, 'L', true);
        
        // Additional summary info
        if ($total_records > 0) {
            $avg_items = $total_items / $total_records;
            $this->Cell(95, 8, '  Rata-rata Item per Request: ' . number_format($avg_items, 1) . ' item', 1, 0, 'L', true);
            $this->Cell(95, 8, '  Status: Data Valid dan Terekam', 1, 1, 'L', true);
        }
    }
    
    function SignatureArea()
    {
        $this->Ln(15);
        
        // Signature area dengan layout yang lebih professional
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(33, 37, 41);
        
        // Tanggal
        $this->Cell(0, 6, 'Bekasi, ' . $this->formatIndonesianDate(date('Y-m-d')), 0, 1, 'R');
        $this->Ln(8);
        
        // Tanda tangan area
        $signatures = [
            ['title' => 'Dibuat Oleh:', 'name' => 'Staff Inventory', 'pos' => 50],
            ['title' => 'Diperiksa Oleh:', 'name' => 'Supervisor', 'pos' => 105],
            ['title' => 'Disetujui Oleh:', 'name' => 'Manager Operasional', 'pos' => 160]
        ];
        
        foreach ($signatures as $sig) {
            $this->SetXY($sig['pos'] - 25, $this->GetY());
            $this->Cell(50, 6, $sig['title'], 0, 0, 'C');
        }
        $this->Ln(6);
        
        foreach ($signatures as $sig) {
            $this->SetXY($sig['pos'] - 25, $this->GetY());
            $this->Cell(50, 6, $sig['name'], 0, 0, 'C');
        }
        $this->Ln(20);
        
        // Signature lines
        foreach ($signatures as $sig) {
            $this->SetXY($sig['pos'] - 20, $this->GetY());
            $this->Cell(40, 0.5, '', 'T', 0, 'C');
        }
        $this->Ln(6);
        
        // Names and signatures
        foreach ($signatures as $sig) {
            $this->SetXY($sig['pos'] - 25, $this->GetY());
            $this->Cell(50, 6, '( Nama & Tanda Tangan )', 0, 0, 'C');
        }
    }
    
    // Helper functions
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    
    function formatIndonesianDate($date)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $date_obj = new DateTime($date);
        $day = $date_obj->format('d');
        $month = $months[(int)$date_obj->format('m')];
        $year = $date_obj->format('Y');
        
        return "$day $month $year";
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
}

// Main execution dengan error handling yang lebih baik
try {
    // Cek apakah data POST tersedia
    if (!isset($_POST['bln']) || !isset($_POST['thn'])) {
        throw new Exception("Parameter bulan dan tahun harus diisi!");
    }
    
    $bln = trim($_POST['bln']);
    $thn = trim($_POST['thn']);
    
    // Validasi input yang lebih ketat
    if (!is_numeric($thn) || $thn < 2020 || $thn > date('Y') + 5) {
        throw new Exception("Tahun harus berupa angka antara 2020 hingga " . (date('Y') + 5));
    }
    
    if ($bln !== 'all' && (!is_numeric($bln) || $bln < 1 || $bln > 12)) {
        throw new Exception("Bulan harus berupa angka antara 1-12 atau 'all'");
    }
    
    // Database connection dengan timeout dan charset
    $koneksi = new mysqli("localhost", "pora5278_fahmi", "Au1b839@@", "pora5278_inventrizki");
    $koneksi->set_charset("utf8");
    
    if ($koneksi->connect_error) {
        throw new Exception("Koneksi database gagal: " . $koneksi->connect_error);
    }
    
    // Query dengan prepared statement
    if ($bln == 'all') {
        $stmt = $koneksi->prepare("
            SELECT id_request, tanggal, kode_barang, nama_barang, supplier, jumlah 
            FROM request_barang 
            WHERE YEAR(tanggal) = ? 
            ORDER BY tanggal DESC, id_request DESC
        ");
        $stmt->bind_param("s", $thn);
    } else {
        $stmt = $koneksi->prepare("
            SELECT id_request, tanggal, kode_barang, nama_barang, supplier, jumlah 
            FROM request_barang 
            WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ? 
            ORDER BY tanggal DESC, id_request DESC
        ");
        $stmt->bind_param("ss", $bln, $thn);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Error executing query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    // Bersihkan buffer output
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers untuk download PDF
    header('Content-Type: application/pdf');
    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    
    // Buat PDF
    $pdf = new RequestBarangPDF($bln, $thn);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 30);
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
            if ($pdf->GetY() > 240) {
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
        
        // Area tanda tangan
        $pdf->SignatureArea();
        
    } else {
        $pdf->SetFont('Arial', 'I', 14);
        $pdf->SetTextColor(108, 117, 125);
        $pdf->Ln(20);
        $pdf->Cell(0, 15, '📊 Tidak ada data untuk periode yang dipilih', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, 'Silakan pilih periode waktu yang berbeda', 0, 1, 'C');
    }
    
    // Output PDF dengan nama file yang lebih descriptive
    $period = ($bln == 'all') ? "Tahun_$thn" : "Bulan_" . str_pad($bln, 2, '0', STR_PAD_LEFT) . "_$thn";
    $filename = "Laporan_Request_Barang_{$period}_" . date('d-m-Y_H-i-s') . '.pdf';
    
    $pdf->Output('D', $filename);
    
    $stmt->close();
    $koneksi->close();
    
} catch (Exception $e) {
    // Error handling dengan tampilan yang lebih baik
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - Export PDF</title>
        <style>
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                margin: 0; 
                padding: 40px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .error-container {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                text-align: center;
                max-width: 500px;
                width: 100%;
            }
            .error-icon {
                font-size: 4rem;
                margin-bottom: 20px;
            }
            .error-title {
                color: #dc3545;
                font-size: 1.8rem;
                margin-bottom: 15px;
                font-weight: 600;
            }
            .error-message {
                background: #f8d7da;
                color: #721c24;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                border-left: 4px solid #dc3545;
            }
            .btn-back {
                background: linear-gradient(45deg, #007bff, #0056b3);
                color: white;
                padding: 12px 30px;
                border: none;
                border-radius: 25px;
                cursor: pointer;
                font-size: 1rem;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }
            .btn-back:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,123,255,0.3);
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">❌</div>
            <h2 class="error-title">Oops! Terjadi Kesalahan</h2>
            <div class="error-message">
                <?= htmlspecialchars($e->getMessage()) ?>
            </div>
            <button onclick="history.back()" class="btn-back">
                ← Kembali ke Halaman Sebelumnya
            </button>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>