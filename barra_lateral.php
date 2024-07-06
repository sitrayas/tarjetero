<?php
// Incluye 'conexion.php' si aún no lo has incluido
if (!isset($conn)) {
    include('conexion.php');
}

// Verifica si la sesión ya está iniciada antes de iniciarla nuevamente
if (!isset($_SESSION)) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuarioingresando'])) {
    header('location: index.php');
    exit();
}

$usuarioingresado = $_SESSION['usuarioingresando'];
$buscandousu = mysqli_query($conn, "SELECT * FROM usuarios WHERE correo = '$usuarioingresado'");
$mostrar = mysqli_fetch_array($buscandousu);

if (!$mostrar) {
    echo "Error: Usuario no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tarjetero</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="BarraLateral">
<ul>
    USUARIO: 
    <div class="NomUsuario"><?php echo htmlspecialchars($mostrar['nom'], ENT_QUOTES, 'UTF-8'); ?></div>
   
    <hr>
    <li><a href="principal.php">Inicio</a></li>
   <!-- <li><a href="usuarios_tabla.php">Usuarios</a></li>    -->
    <li><a href="registrar_hc.php">Ingresar HC</a></li>
	<li><a href="consultar_hc.php">Consultar HC</a></li>
	<li><a href="registrar_hc.php">Modificar HC</a></li>
	
    <li><a href="cerrar_sesion.php">Cerrar sesión</a></li>
</ul>
</div>
</body>
</html>
