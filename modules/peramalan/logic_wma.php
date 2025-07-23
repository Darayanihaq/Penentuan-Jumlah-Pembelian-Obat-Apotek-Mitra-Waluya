<?php

function hitungWMAArray(array $data): array
{
    if (count($data) < 4)
        return [];

    $weights = [0.1, 0.2, 0.3, 0.4];
    $n = count($data);
    $result = [];

    for ($i = 3; $i < $n; $i++) {
        $numerator = 0;
        $denominator = 0;
        for ($j = 0; $j < 4; $j++) {
            $numerator += $data[$i - 3 + $j] * $weights[$j];
            $denominator += $weights[$j];
        }
        $result[] = $denominator > 0 ? round($numerator / $denominator, 2) : 0;
    }
    return $result;
}


function hitungMAD($aktual, $forecast)
{
    $n = count($aktual);
    $total = 0;
    for ($i = 0; $i < $n; $i++) {
        $total += abs($aktual[$i] - $forecast[$i]);
    }
    return round($total / $n, 2);
}

function hitungMAPE(array $aktual, array $forecast): float
{
    $n = count($aktual);

    if ($n === 0 || $n !== count($forecast)) {
        return 0; // data tidak valid
    }

    $sum_error = 0;
    $valid_data = 0;

    for ($i = 0; $i < $n; $i++) {
        if ($aktual[$i] == 0)
            continue; // hindari pembagian nol

        $error = abs($aktual[$i] - $forecast[$i]) / $aktual[$i];
        $sum_error += $error;
        $valid_data++;
    }

    return $valid_data > 0 ? round(($sum_error / $valid_data) * 100, 2) : 0;
}



