<?php
function generateUserId($conn)
{
    $result = mysqli_query($conn, "SELECT MAX(id_user) AS max_id FROM pengguna");
    $data = mysqli_fetch_assoc($result);
    $last_id = $data['max_id'];

    if ($last_id) {
        $num = (int) substr($last_id, 3);
        $num++;
        return 'USR' . str_pad($num, 2, '0', STR_PAD_LEFT);
    } else {
        return 'USR01';
    }
}

function getDataPenggunaById($conn, $id)
{
    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_user = '$id'");
    return mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : null;
}
