<?php
include('conexion.php');

// Consultar el próximo número de historia clínica disponible
$resultado = mysqli_query($conn, "SELECT MAX(numero_historia_clinica) AS maximo FROM Pacientes");
$ultimo_registro = mysqli_fetch_assoc($resultado);
$siguiente_numero = $ultimo_registro['maximo'] + 1;

echo $siguiente_numero;
?>
