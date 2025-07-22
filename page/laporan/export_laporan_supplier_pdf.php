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
        $this->Cell(30,10,'LAPORAN DATA SUPPLIER',0,0,'C');
        
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
        $header = array('No', 'Kode Supplier', 'Nama Supplier', 'Alamat', 'Telepon');
        $w = array(15, 30, 50, 60, 35); // Lebar kolom disesuaikan dengan data supplier
        
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
        $w = array(15, 30, 50, 60, 35);
        
        // Hitung tinggi sel berdasarkan alamat (yang mungkin panjang)
        $nb = 0;
        $nb = max($nb, $this->NbLines($w[3], $data['alamat']));
        $h = 7 * max(1, $nb);
        
        // Cek apakah perlu halaman baru
        $this->CheckPageBreak($h);
        
        // Simpan posisi X dan Y saat ini
        $x = $this->GetX();
        $y = $this->GetY();
        
        // Data dengan MultiCell untuk alamat yang panjang
        $this->Cell($w[0],$h,$no,'LR',0,'C',true);
        $this->Cell($w[1],$h,$data['kode_supplier'],'LR',0,'L',true);
        $this->Cell($w[2],$h,$data['nama_supplier'],'LR',0,'L',true);
        
        // Untuk alamat, gunakan MultiCell jika terlalu panjang
        $this->SetXY($x + $w[0] + $w[1] + $w[2], $y);
        $this->MultiCell($w[3], 7, $data['alamat'], 'LR', 'L', true);
        
        // Pindah ke kolom telepon
        $this->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3], $y);
        $this->Cell($w[4],$h,$data['telepon'],'LR',0,'L',true);
        
        $this->SetXY($x, $y + $h);
    }
    
    // Fungsi untuk menghitung jumlah baris yang dibutuhkan
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb)
        {
            $c = $s[$i];
            if($c=="\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
    
    // Cek apakah perlu page break
    function CheckPageBreak($h)
    {
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }
    
    // Garis penutup tabel
    function TableClose()
    {
        $this->Cell(190,0,'','T');
    }
    
    // Summary atau total
    function Summary($total_supplier)
    {
        $this->Ln(10);
        
        // Background untuk summary
        $this->SetFillColor(46, 125, 50); // Hijau
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial','B',10);
        
        $this->Cell(95,8,'Total Supplier Terdaftar: '.$total_supplier,1,0,'L',true);
        $this->Cell(95,8,'Status: Aktif',1,0,'L',true);
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
$total_supplier = 0;

$sql = $koneksi->query("SELECT * FROM tb_supplier ORDER BY nama_supplier ASC");
while ($data = $sql->fetch_assoc()) {
    $pdf->TableData($data, $no);
    $no++;
    $total_supplier++;
}

// Tutup tabel
$pdf->TableClose();

// Tampilkan summary
$pdf->Summary($total_supplier);

// Tambahan informasi di footer
$pdf->Ln(15);
$pdf->SetFont('Arial','I',8);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0,10,'Laporan ini digenerate otomatis pada '.date('d-m-Y H:i:s'),0,0,'L');

// Output PDF
$pdf->Output('D', 'Laporan_Data_Supplier_'.date('d-m-Y').'.pdf');
?>