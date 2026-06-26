<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $laporanList = Barang::allAsc();

        View::render('laporan/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'laporanList' => $laporanList,
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function exportExcel(): void
    {
        Auth::checkLogin();

        while (ob_get_level()) {
            ob_end_clean();
        }

        $laporanList = Barang::allAsc();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN STOK BARANG GUDANG');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Dicetak pada: ' . date('d M Y H:i') . ' | Oleh: ' . Auth::namaUser());
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);

        $headers = ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Sisa Stok', 'Batas Minimum', 'Harga Beli Satuan', 'Total Nilai Aset'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($headers as $i => $header) {
            $cell = $cols[$i] . '4';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9E1F2');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $rowNum = 5;
        $grandTotal = 0;
        $no = 1;
        foreach ($laporanList as $row) {
            $totalAset = $row->totalNilaiAset();
            $grandTotal += $totalAset;

            $sheet->setCellValue('A' . $rowNum, $no);
            $sheet->setCellValue('B' . $rowNum, $row->kode_barang);
            $sheet->setCellValue('C' . $rowNum, $row->nama_barang);
            $sheet->setCellValue('D' . $rowNum, $row->nama_kategori ?? '-');
            $sheet->setCellValue('E' . $rowNum, $row->stok);
            $sheet->setCellValue('F' . $rowNum, $row->stok_min);
            $sheet->setCellValue('G' . $rowNum, $row->harga_beli);
            $sheet->setCellValue('H' . $rowNum, $totalAset);

            $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $rowNum . ':H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            if ($row->isStokKritis()) {
                $sheet->getStyle('E' . $rowNum)->getFont()->getColor()->setARGB('FFFF0000');
            }

            $rowNum++;
            $no++;
        }

        $sheet->setCellValue('A' . $rowNum, 'GRAND TOTAL NILAI ASET');
        $sheet->mergeCells('A' . $rowNum . ':G' . $rowNum);
        $sheet->setCellValue('H' . $rowNum, $grandTotal);
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan_Stok_Gudang_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
