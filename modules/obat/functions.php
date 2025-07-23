<?php

function getPrefixJenis($jenis)
{
    return match ($jenis) {
        'Tablet Generik' => 'G',
        'Tablet Paten' => 'P',
        'Sirup' => 'SY',
        'Vitamin' => 'V',
        'Salep' => 'SL',
        default => 'XX',
    };
}

function generateKodeObat($conn, $jenis)
{
    $prefix = getPrefixJenis($jenis);
    $query = mysqli_query($conn, "SELECT kode_obat FROM obat WHERE kode_obat LIKE '$prefix-%' ORDER BY kode_obat DESC LIMIT 1");
    $last = mysqli_fetch_assoc($query);
    $new_number = '001';
    if ($last) {
        $last_number = (int) substr($last['kode_obat'], strlen($prefix) + 1);
        $new_number = str_pad($last_number + 1, 3, '0', STR_PAD_LEFT);
    }
    return $prefix . '-' . $new_number;
}
