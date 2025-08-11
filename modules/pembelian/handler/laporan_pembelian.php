<?php
require_once '../../../config/db.php';

// Cek apakah PHPSpreadsheet tersedia
if (file_exists('../../../vendor/autoload.php')) {
    require_once '../../../vendor/autoload.php';
    generateExcelWithPhpSpreadsheet();
} else {
    generateCSVFallback();
}

function generateExcelWithPhpSpreadsheet()
{
    global $conn;

    // Membuat spreadsheet baru
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ========== PENGATURAN HEADER LAPORAN ==========

    // Judul utama
    $sheet->setCellValue('A1', 'LAPORAN DETAIL PEMBELIAN OBAT');
    $sheet->mergeCells('A1:K1');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => '2E7D32']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ]
    ]);

    // Sub judul
    $sheet->setCellValue('A2', 'Apotek Mitra Waluya');
    $sheet->mergeCells('A2:K2');
    $sheet->getStyle('A2')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12,
            'color' => ['rgb' => '424242']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ]
    ]);

    // Info tanggal dan filter
    $filterInfo = 'Periode: ';
    if (!empty($_GET['bulan']) && !empty($_GET['tahun'])) {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $filterInfo .= $bulanIndonesia[(int) $_GET['bulan']] . ' ' . $_GET['tahun'];
    } else {
        $filterInfo .= 'Semua Data';
    }
    $filterInfo .= ' | Dicetak: ' . date('d/m/Y H:i:s');

    $sheet->setCellValue('A3', $filterInfo);
    $sheet->mergeCells('A3:K3');
    $sheet->getStyle('A3')->applyFromArray([
        'font' => ['size' => 10, 'italic' => true],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
    ]);

    // ========== PENGATURAN HEADER TABEL ==========

    $headers = [
        'A5' => 'No',
        'B5' => 'ID Pembelian',
        'C5' => 'Kode Obat',
        'D5' => 'Nama Obat',
        'E5' => 'Jenis Obat',
        'F5' => 'Harga Satuan (Rp)',
        'G5' => 'Jumlah Pembelian',
        'H5' => 'Supplier',
        'I5' => 'Periode',
        'J5' => 'MAD',
        'K5' => 'MAPE (%)',
        'L5' => 'Total Pembelian (Rp)'
    ];

    foreach ($headers as $cell => $header) {
        $sheet->setCellValue($cell, $header);
    }

    // Style header tabel
    $headerRange = 'A5:L5';
    $sheet->getStyle($headerRange)->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 11
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4CAF50']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ]);

    // ========== PENGATURAN LEBAR KOLOM ==========

    $sheet->getColumnDimension('A')->setWidth(8);   // No
    $sheet->getColumnDimension('B')->setWidth(12);  // ID Pembelian
    $sheet->getColumnDimension('C')->setWidth(12);  // Kode Obat
    $sheet->getColumnDimension('D')->setWidth(25);  // Nama Obat
    $sheet->getColumnDimension('E')->setWidth(15);  // Jenis Obat
    $sheet->getColumnDimension('F')->setWidth(18);  // Harga Satuan
    $sheet->getColumnDimension('G')->setWidth(15);  // Jumlah Pembelian
    $sheet->getColumnDimension('H')->setWidth(20);  // Supplier
    $sheet->getColumnDimension('I')->setWidth(15);  // Periode
    $sheet->getColumnDimension('J')->setWidth(12);  // MAD
    $sheet->getColumnDimension('K')->setWidth(12);  // MAPE
    $sheet->getColumnDimension('L')->setWidth(20);  // Total Pembelian

    // ========== QUERY DAN PENGISIAN DATA ==========

    $query = "SELECT p.id_pembelian, p.kode_obat, o.nama_obat, o.jenis, o.harga_obat as harga, 
                     p.jml_pembelian, s.nama_supplier, p.bulan_pembelian, p.tahun_pembelian,
                     r.hasil_peramalan, r.mad_peramalan, r.mape_peramalan, r.bulan_peramalan,
                     (p.jml_pembelian * o.harga_obat) as total_pembelian
              FROM pembelian p
              LEFT JOIN obat o ON o.kode_obat = p.kode_obat
              LEFT JOIN supplier s ON s.id_supplier = p.id_supplier
              LEFT JOIN peramalan r ON p.id_peramalan = r.id_peramalan";

    // Filter berdasarkan parameter
    $whereConditions = [];
    if (!empty($_GET['bulan'])) {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $namaBulan = $bulanIndonesia[(int) $_GET['bulan']];
        $whereConditions[] = "p.bulan_pembelian = '" . mysqli_real_escape_string($conn, $namaBulan) . "'";
    }
    if (!empty($_GET['tahun'])) {
        $whereConditions[] = "p.tahun_pembelian = '" . mysqli_real_escape_string($conn, $_GET['tahun']) . "'";
    }

    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(" AND ", $whereConditions);
    }

    $query .= " ORDER BY p.tahun_pembelian DESC, 
                FIELD(p.bulan_pembelian, 'Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember') DESC";

    $result = mysqli_query($conn, $query);

    // Mengisi data ke spreadsheet
    $row = 6; // Mulai dari baris 6
    $no = 1;
    $totalKeseluruhan = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            // Data untuk setiap kolom
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['id_pembelian']);
            $sheet->setCellValue('C' . $row, $data['kode_obat']);
            $sheet->setCellValue('D' . $row, $data['nama_obat']);
            $sheet->setCellValue('E' . $row, $data['jenis']);
            $sheet->setCellValue('F' . $row, $data['harga']);
            $sheet->setCellValue('G' . $row, $data['jml_pembelian']);
            $sheet->setCellValue('H' . $row, $data['nama_supplier']);
            $sheet->setCellValue('I' . $row, $data['bulan_pembelian'] . ' ' . $data['tahun_pembelian']);
            $sheet->setCellValue('J' . $row, $data['mad_peramalan'] ? number_format($data['mad_peramalan'], 2) : '-');
            $sheet->setCellValue('K' . $row, $data['mape_peramalan'] ? number_format($data['mape_peramalan'], 2) : '-');
            $sheet->setCellValue('L' . $row, $data['total_pembelian']);

            // ========== FORMATTING DATA ==========

            // Format nomor dan alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format ID Pembelian - center
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format Kode Obat - center
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format nama obat - wrap text jika panjang
            $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);

            // Format jenis obat - center
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format harga satuan - currency
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            // Format jumlah pembelian - number
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format supplier
            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);

            // Format jumlah pembelian - center
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format supplier - left
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Format periode - center
            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Format MAD - decimal
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('0.00');
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            // Format MAPE - decimal
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('0.00');
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            // Format total pembelian - currency
            $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            // Border untuk setiap baris data
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ]
            ]);

            // Alternate row color untuk readability
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9F9F9']
                    ]
                ]);
            }

            $totalKeseluruhan += $data['total_pembelian'];
            $row++;
        }
    } else {
        // Jika tidak ada data
        $sheet->setCellValue('A6', 'Tidak ada data pembelian untuk periode yang dipilih');
        $sheet->mergeCells('A6:L6');
        $sheet->getStyle('A6')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'font' => ['italic' => true, 'color' => ['rgb' => '757575']]
        ]);
        $row = 7;
    }

    // ========== BARIS TOTAL ==========

    if ($totalKeseluruhan > 0) {
        $sheet->setCellValue('A' . $row, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->setCellValue('L' . $row, $totalKeseluruhan);

        // Style untuk baris total
        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '1B5E20']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F5E8']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4CAF50']
                ]
            ]
        ]);

        // Format currency untuk total
        $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    }

    // ========== FREEZE PANES ==========
    $sheet->freezePane('A6'); // Freeze header

    // ========== OUTPUT FILE ==========

    // ========== OUTPUT FILE ==========
    $namaBulan = '';
    if (!empty($_GET['bulan'])) {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $namaBulan = $bulanIndonesia[(int) $_GET['bulan']] . '-';
    }

    $tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
    $filename = 'Laporan-Pembelian-' . $namaBulan . $tahun . '.xlsx';

    // Jika tidak ada filter bulan dan tahun
    if (empty($_GET['bulan']) && empty($_GET['tahun'])) {
        $filename = 'Laporan-Pembelian-Semua-Data.xlsx';
    }

    // Set headers untuk download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

function generateCSVFallback()
{
    global $conn;

    // ========== PENENTUAN NAMA FILE ==========
    $namaBulan = '';
    if (!empty($_GET['bulan'])) {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $namaBulan = $bulanIndonesia[(int) $_GET['bulan']] . '-';
    }

    $tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';
    $filename = 'Laporan-Pembelian-' . $namaBulan . $tahun . '.csv';

    // Jika tidak ada filter bulan dan tahun
    if (empty($_GET['bulan']) && empty($_GET['tahun'])) {
        $filename = 'Laporan-Pembelian-Semua-Data.csv';
    }

    // Set headers untuk download
    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=" . $filename);

    $output = fopen("php://output", "w");

    // BOM untuk UTF-8
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Header CSV
    fputcsv($output, [
        'No',
        'ID Pembelian',
        'Kode Obat',
        'Nama Obat',
        'Jenis Obat',
        'Harga Satuan (Rp)',
        'Jumlah Pembelian',
        'Supplier',
        'Periode',
        'Total Pembelian (Rp)'
    ]);

    // Query data
    $query = "SELECT p.id_pembelian, p.kode_obat, o.nama_obat, o.jenis, o.harga_obat as harga, 
                     p.jml_pembelian, s.nama_supplier, p.bulan_pembelian, p.tahun_pembelian,
                     (p.jml_pembelian * o.harga_obat) as total_pembelian
              FROM pembelian p
              LEFT JOIN obat o ON o.kode_obat = p.kode_obat
              LEFT JOIN supplier s ON s.id_supplier = p.id_supplier";

    $whereConditions = [];
    if (!empty($_GET['bulan'])) {
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $namaBulan = $bulanIndonesia[(int) $_GET['bulan']];
        $whereConditions[] = "p.bulan_pembelian = '" . mysqli_real_escape_string($conn, $namaBulan) . "'";
    }
    if (!empty($_GET['tahun'])) {
        $whereConditions[] = "p.tahun_pembelian = '" . mysqli_real_escape_string($conn, $_GET['tahun']) . "'";
    }

    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(" AND ", $whereConditions);
    }

    $query .= " ORDER BY p.tahun_pembelian DESC, 
                FIELD(p.bulan_pembelian, 'Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember') DESC";

    $result = mysqli_query($conn, $query);

    $no = 1;
    $totalKeseluruhan = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $no++,
            $row['id_pembelian'],
            $row['kode_obat'],
            $row['nama_obat'],
            $row['jenis'],
            number_format($row['harga'], 0, ',', '.'),
            $row['jml_pembelian'],
            $row['nama_supplier'],
            $row['bulan_pembelian'] . ' ' . $row['tahun_pembelian'],
            number_format($row['total_pembelian'], 0, ',', '.')
        ]);
        $totalKeseluruhan += $row['total_pembelian'];
    }

    // Baris Total
    fputcsv($output, [
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'TOTAL KESELURUHAN',
        number_format($totalKeseluruhan, 0, ',', '.')
    ]);

    fclose($output);
    exit;
}