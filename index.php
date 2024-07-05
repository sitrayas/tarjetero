<?php
session_start();
include('conexion.php');
if(isset($_SESSION['usuarioingresando']))
{
	header('location: principal.php');
}
?>

<html>
<!--Busca por VaidrollTeam para más proyectos. -->
<head>
<title>VaidrollTeam</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="FormCajaLogin">

<div class="FormLogin">
<form method="POST">
<h1>Iniciar sesión</h1>
<br>

<div class="TextoCajas">• Ingresar correo</div>
<input type="text" name="txtcorreo" class="CajaTexto" required>

<div class="TextoCajas">• Ingresar password</div>
<input type="password" id="txtpassword" name="txtpassword" class="CajaTexto" required>

<div class="CheckBox1">
<input type="checkbox" onclick="verpassword()" >Mostrar password
</div>

<div>
<input type="submit" value="Iniciar sesión" class="BtnLogin" name="btningresar" >
</div>
<hr>
<br>

<div>
<a href="usuarios_registrar.php" class="BtnRegistrar">Crea nueva cuenta</a>
</div>

</div>
</form>
</div>
 
</body>
<script>

function verpassword()
  {
  var tipo = document.getElementById("txtpassword");
    if(tipo.type == "password")
	  {
        tipo.type = "text";
      }
	  else
	  {
        tipo.type = "password";
      }
  }
</script>
</html>
<?php
if(isset($_POST['btningresar']))
{
$correo = $_POST["txtcorreo"];
$pass 	= $_POST["txtpassword"];

$buscandousu = mysqli_query($conn,"SELECT * FROM usuarios WHERE correo = '".$correo."' and pass = '".$pass."'");
$nr = mysqli_num_rows($buscandousu);

if($nr == 1)
{
$_SESSION['usuarioingresando']=$correo;
header("Location: principal.php");
}
else if ($nr == 0) 
{
echo "<script> alert('Usuario no existe');window.location= 'index.php' </script>";
}
}
?>