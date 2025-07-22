<?php
require('../../vendor/fpdf/fpdf.php');

// Koneksi database
$koneksi = new mysqli("localhost","pora5278_fahmi","Au1b839@@","pora5278_inventrizki");

// Class PDF custom
class PDF extends FPDF
{
    // Header
    function Header()
    {
        // Logo atau gambar (opsional)
        // $this->Image('logo.png',10,6,30);
        
        // Font untuk judul
        $this->SetFont('Arial','B',16);
        
        // Pindah ke kanan
        $this->Cell(80);
        
        // Judul
        $this->Cell(30,10,'LAPORAN STOK GUDANG',0,0,'C');
        
        // Line break
        $this->Ln(10);
        
        // Font untuk tanggal
        $this->SetFont('Arial','',10);
        $this->Cell(80);
        $this->Cell(30,10,'Tanggal: '.date('d-m-Y'),0,0,'C');
        
        // Line break
        $this->Ln(20);
    }

    // Footer
    function Footer()
    {
        // Posisi 1.5 cm dari bawah
        $this->SetY(-15);
        
        // Font Arial italic 8
        $this->SetFont('Arial','I',8);
        
        // Nomor halaman
        $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
    }
    
    // Tabel header yang lebih bagus
    function TableHeader()
    {
        // Background color untuk header
        $this->SetFillColor(52, 73, 94); // Warna biru gelap
        $this->SetTextColor(255, 255, 255); // Warna teks putih
        $this->SetDrawColor(52, 73, 94); // Warna border
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','B',9);
        
        // Header tabel
        $header = array('No', 'Kode Barang', 'Nama Barang', 'Jenis Barang', 'Supplier', 'Jumlah', 'Satuan');
        $w = array(10, 25, 40, 30, 35, 20, 20); // Lebar kolom
        
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
        $this->Ln();
    }
    
    // Isi tabel
    function TableData($data, $no)
    {
        // Warna background bergantian
        if($no % 2 == 0) {
            $this->SetFillColor(236, 240, 241); // Abu-abu muda
        } else {
            $this->SetFillColor(255, 255, 255); // Putih
        }
        
        $this->SetTextColor(0, 0, 0); // Warna teks hitam
        $this->SetFont('Arial','',8);
        
        // Lebar kolom
        $w = array(10, 25, 40, 30, 35, 20, 20);
        
        // Data
        $this->Cell($w[0],7,$no,'LR',0,'C',true);
        $this->Cell($w[1],7,$data['kode_barang'],'LR',0,'L',true);
        $this->Cell($w[2],7,$data['nama_barang'],'LR',0,'L',true);
        $this->Cell($w[3],7,$data['jenis_barang'],'LR',0,'L',true);
        $this->Cell($w[4],7,$data['supplier'],'LR',0,'L',true);
        $this->Cell($w[5],7,number_format($data['jumlah']),'LR',0,'C',true);
        $this->Cell($w[6],7,$data['satuan'],'LR',0,'C',true);
        $this->Ln();
    }
    
    // Garis penutup tabel
    function TableClose()
    {
        $this->Cell(180,0,'','T');
    }
    
    // Summary atau total
    function Summary($total_items, $total_barang)
    {
        $this->Ln(10);
        
        // Background untuk summary
        $this->SetFillColor(46, 125, 50); // Hijau
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial','B',10);
        
        $this->Cell(90,8,'Total Jenis Barang: '.$total_items,1,0,'L',true);
        $this->Cell(90,8,'Total Stok: '.number_format($total_barang).' Unit',1,0,'L',true);
        $this->Ln();
    }
}

// Buat objek PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Header tabel
$pdf->TableHeader();

// Ambil data dari database
$no = 1;
$total_items = 0;
$total_barang = 0;

$sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang ASC");
while ($data = $sql->fetch_assoc()) {
    // Cek apakah perlu halaman baru
    if($pdf->GetY() > 250) {
        $pdf->AddPage();
        $pdf->TableHeader();
    }
    
    $pdf->TableData($data, $no);
    $no++;
    $total_items++;
    $total_barang += $data['jumlah'];
}

// Tutup tabel
$pdf->TableClose();

// Tampilkan summary
$pdf->Summary($total_items, $total_barang);

// Tambahan informasi di footer
$pdf->Ln(15);
$pdf->SetFont('Arial','I',8);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0,10,'Laporan ini digenerate otomatis pada '.date('d-m-Y H:i:s'),0,0,'L');

// Output PDF
$pdf->Output('D', 'Laporan_Stok_Gudang_'.date('d-m-Y').'.pdf');
?>