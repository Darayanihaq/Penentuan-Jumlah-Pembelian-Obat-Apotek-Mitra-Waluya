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


function hitungMAD(array $aktual, array $forecast): float
{
    $n = count($aktual);
    $totalError = 0;

    foreach ($aktual as $value) {
        $totalError += abs($value - $forecast[0]);
    }

    return round($totalError / $n, 2);
}

function hitungMAPE(array $aktual, array $forecast): float
{
    $n = count($aktual);
    $totalPercentage = 0;
    $validCount = 0;

    foreach ($aktual as $value) {
        if ($value != 0) {
            $totalPercentage += abs($value - $forecast[0]) / $value;
            $validCount++;
        }
    }

    return $validCount > 0 ? round(($totalPercentage / $validCount) * 100, 2) : 0.0;
}


function getKategoriMAPE(float $mape): string
{
    if ($mape < 10) {
        return "Sangat Baik";
    } elseif ($mape < 20) {
        return "Baik";
    } elseif ($mape < 50) {
        return "Layak";
    } else {
        return "Buruk";
    }
}


