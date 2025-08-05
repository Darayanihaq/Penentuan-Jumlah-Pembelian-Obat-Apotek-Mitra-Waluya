<?php
function generateSupplierId($conn)
{
    $result = mysqli_query($conn, "SELECT MAX(id_supplier) AS max_id FROM supplier");
    $data = mysqli_fetch_assoc($result);
    $last_id = $data['max_id'];

    if ($last_id) {
        $num = (int) substr($last_id, 3);
        $num++;
        return 'SP-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        return 'SP-001';
    }
}

function getSupplierById($conn, $id)
{
    $id = mysqli_real_escape_string($conn, $id);
    $result = mysqli_query($conn, "SELECT * FROM supplier WHERE id_supplier = '$id'");
    return (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
}
