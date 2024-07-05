<?php
include('conexion.php');

if (isset($_POST['canton_id'])) {
    $canton_id = $_POST['canton_id'];
    $query = "SELECT * FROM tbl_parroquia WHERE id_canton = '$canton_id'";
    $result = mysqli_query($conn, $query);

    echo '<option value="">Seleccione...</option>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['id'] . '">' . $row['parroquia'] . '</option>';
    }
}
?>
