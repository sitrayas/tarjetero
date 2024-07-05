<?php
include('conexion.php');

if (isset($_POST['provincia_id'])) {
    $provincia_id = $_POST['provincia_id'];
    $query = "SELECT * FROM tbl_canton WHERE id_provincia = '$provincia_id'";
    $result = mysqli_query($conn, $query);

    echo '<option value="">Seleccione...</option>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row['id'] . '">' . $row['canton'] . '</option>';
    }
}
?>
