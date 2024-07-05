<?php
session_start();
include('conexion.php');

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

$numero_historia_clinica_asignado = ''; // Variable para almacenar el número de historia clínica asignado
$mensaje_registro = ''; // Variable para almacenar el mensaje de registro

// Procesar el formulario de registro de paciente
if(isset($_POST['btnregistrar'])) {
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $nombres = $_POST['nombres'];
    $nombre_del_padre = $_POST['nombre_del_padre'];
    $nombre_de_la_madre = $_POST['nombre_de_la_madre'];
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
    $sexo = $_POST['sexo'];
    $cedula = $_POST['cedula'];
    $direccion = $_POST['direccion'];
    $provincia_id = $_POST['provincia'];
    $canton_id = $_POST['canton'];
    $parroquia_id = $_POST['parroquia'];
    // Validar y sanitizar datos antes de la inserción
    $apellido_paterno = mysqli_real_escape_string($conn, $apellido_paterno);
    $apellido_materno = mysqli_real_escape_string($conn, $apellido_materno);
    $nombres = mysqli_real_escape_string($conn, $nombres);
    $nombre_del_padre = mysqli_real_escape_string($conn, $nombre_del_padre);
    $nombre_de_la_madre = mysqli_real_escape_string($conn, $nombre_de_la_madre);
    $fecha_de_nacimiento = mysqli_real_escape_string($conn, $fecha_de_nacimiento);
    $sexo = mysqli_real_escape_string($conn, $sexo);
    $cedula = mysqli_real_escape_string($conn, $cedula);
    $direccion = mysqli_real_escape_string($conn, $direccion);
    $provincia_id = mysqli_real_escape_string($conn, $provincia_id);
    $canton_id = mysqli_real_escape_string($conn, $canton_id);;
    $parroquia_id = mysqli_real_escape_string($conn, $direccion);






    
    // Verificar si se ingresó manualmente un número de historia clínica
    if (!empty($_POST['numero_historia_clinica'])) {
        $numero_historia_clinica_manual = $_POST['numero_historia_clinica'];
        
        // Verificar si el número de historia clínica manual ya está asignado
        $query_verificacion = "SELECT * FROM Pacientes WHERE numero_historia_clinica = '$numero_historia_clinica_manual'";
        $result_verificacion = mysqli_query($conn, $query_verificacion);
        
        if (mysqli_num_rows($result_verificacion) > 0) {
            $mensaje_registro = 'Error: El número de historia clínica ingresado manualmente ya está asignado.';
        } else {
         // Insertar datos en la base de datos
         $query = "INSERT INTO Pacientes (apellido_paterno, apellido_materno, nombres, nombre_del_padre, nombre_de_la_madre, fecha_de_nacimiento, sexo, cedula, direccion, numero_historia_clinica, fecha_de_ingreso) 
         VALUES ('$apellido_paterno', '$apellido_materno', '$nombres', '$nombre_del_padre', '$nombre_de_la_madre', '$fecha_de_nacimiento', '$sexo', '$cedula', '$direccion', '$numero_historia_clinica_manual', NOW())";

            if(mysqli_query($conn, $query)) {
                $mensaje_registro = 'Paciente registrado correctamente.';
                // Redirigir a la página de confirmación
                header('location: confirmacion_registro.php');
                exit();
            } else {
                echo "Error al registrar paciente: " . mysqli_error($conn);
            }
            
        }
    } else {
        // Insertar datos en la base de datos sin número de historia clínica manual
        $query = "INSERT INTO Pacientes (apellido_paterno, apellido_materno, nombres, nombre_del_padre, nombre_de_la_madre, fecha_de_nacimiento, sexo, cedula, direccion, fecha_de_ingreso) 
                  VALUES ('$apellido_paterno', '$apellido_materno', '$nombres', '$nombre_del_padre', '$nombre_de_la_madre', '$fecha_de_nacimiento', '$sexo', '$cedula', '$direccion', NOW())";
    
        if(mysqli_query($conn, $query)) {
            $numero_historia_clinica_asignado = mysqli_insert_id($conn); // Obtener el ID autoincremental asignado
            $mensaje_registro = 'Paciente registrado correctamente.';
            // Redirigir a la página de confirmación
            header('location: confirmacion_registro.php');
            exit();
        } else {
            echo "Error al registrar paciente: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Registrar Historia Clínica</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Función para habilitar o deshabilitar el campo de número de historia clínica
    $('#checkbox_manual').change(function() {
        if($(this).is(":checked")) {
            $('#numero_historia_clinica').prop('readonly', false).val('');
        } else {
            $('#numero_historia_clinica').prop('readonly', true).val('');
        }
    });

    // Función para obtener el próximo número de historia clínica al hacer clic en "Ingresar Nuevo"
    $('#link_ingresar_nuevo').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'obtener_proximo_numero.php', // Script PHP para obtener el próximo número
            type: 'GET',
            success: function(response) {
                $('#numero_historia_clinica').val(response);
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener próximo número de historia clínica:', error);
            }
        });
    });

    // Validación antes de enviar el formulario
    $('form').submit(function(e) {
        if($('#checkbox_manual').is(":checked")) {
            var numeroHC = $('#numero_historia_clinica').val().trim();
            if(numeroHC === '') {
                alert('Por favor ingrese un número de historia clínica.');
                e.preventDefault();
            } else {
                // Validación adicional para verificar si el número de historia clínica ya existe
                $.ajax({
                    url: 'verificar_numero_hc.php', // Script PHP para verificar el número de historia clínica
                    type: 'POST',
                    data: { numero_hc: numeroHC },
                    success: function(response) {
                        if(response === 'existe') {
                            alert('El número de historia clínica ingresado ya está asignado.');
                            e.preventDefault();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al verificar número de historia clínica:', error);
                    }
                });
            }
        }
    });
});
</script>
</head>
<body>
<?php include('barra_lateral.php'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1 class="text-center">Registrar Historia Clínica</h1></div>
                <div class="card-body">
                    <?php if (!empty($mensaje_registro)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $mensaje_registro; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">



                    
                    <div class="text-left">
                            
                            <button id="link_ingresar_nuevo" class="btn btn-primary">Ingresar Nuevo</button>
                            
                        </div>




                        <!-- Checkbox para ingresar manualmente el número de historia clínica -->
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox_manual" name="checkbox_manual">
                            <label class="form-check-label" for="checkbox_manual">Ingresar número de historia clínica manualmente</label>
                        </div>






                        <!-- Campo para el número de historia clínica -->
                        <div class="form-group">
                            <label for="numero_historia_clinica">Número de Historia Clínica asignado:</label>
                            <input type="text" id="numero_historia_clinica" name="numero_historia_clinica" class="form-control" readonly>
                        </div>

                        <!-- Resto de campos del formulario -->
                        <div class="form-group">
                            <label for="apellido_paterno">Apellido Paterno:</label>
                            <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" required>
                        </div>



                        <div class="form-group">
                            <label for="apellido_materno">Apellido Materno:</label>
                            <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nombres">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre_del_padre">Nombre del Padre:</label>
                            <input type="text" id="nombre_del_padre" name="nombre_del_padre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre_de_la_madre">Nombre de la Madre:</label>
                            <input type="text" id="nombre_de_la_madre" name="nombre_de_la_madre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_de_nacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Sexo:</label>
                            <select id="sexo" name="sexo" class="form-control" required>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cedula
                            ">Cédula:</label>
                            <input type="text" id="cedula" name="cedula" class="form-control" required>
                        </div>

                       
<!-- Select dinámico para provincias -->
<div class="form-group">
    <label for="provincia">Provincia:</label>
    <select id="provincia" name="provincia" class="form-control" required>
        <option value="">Seleccione...</option>
        <?php
        $query_provincias = "SELECT * FROM tbl_provincia";
        $result_provincias = mysqli_query($conn, $query_provincias);

        while ($row = mysqli_fetch_array($result_provincias)) {
            echo '<option value="' . $row['id'] . '">' . $row['provincia'] . '</option>';
        }
        ?>
    </select>
</div>

<!-- Select dinámico para cantones -->
<div class="form-group">
    <label for="canton">Cantón:</label>
    <select id="canton" name="canton" class="form-control" required>
        <option value="">Seleccione...</option>
    </select>
</div>

<!-- Select dinámico para parroquias -->
<div class="form-group">
    <label for="parroquia">Parroquia:</label>
    <select id="parroquia" name="parroquia" class="form-control" required>
        <option value="">Seleccione...</option>
    </select>
</div>


                        
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <textarea id="direccion" name="direccion" class="form-control" required></textarea>
                        </div>


                        <!-- Campo oculto para la hora de registro -->
                        <input type="hidden" name="hora_registro" value="<?php echo isset($hora_registro) ? $hora_registro : ''; ?>">

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="btnregistrar">Registrar</button>
                            
                            <a href="index.php" class="btn btn-danger">Salir</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<script>
$(document).ready(function() {
    // Cargar select de cantones al seleccionar una provincia
    $('#provincia').change(function() {
        var provincia_id = $(this).val();
        if (provincia_id !== '') {
            $.ajax({
                url: 'obtener_cantones.php', // Script PHP para obtener los cantones de la provincia seleccionada
                type: 'POST',
                data: { provincia_id: provincia_id },
                success: function(response) {
                    $('#canton').html(response);
                    $('#parroquia').html('<option value="">Seleccione...</option>'); // Reiniciar select de parroquias
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar cantones:', error);
                }
            });
        } else {
            $('#canton').html('<option value="">Seleccione...</option>');
            $('#parroquia').html('<option value="">Seleccione...</option>');
        }
    });

    // Cargar select de parroquias al seleccionar un cantón
    $('#canton').change(function() {
        var canton_id = $(this).val();
        if (canton_id !== '') {
            $.ajax({
                url: 'obtener_parroquias.php', // Script PHP para obtener las parroquias del cantón seleccionado
                type: 'POST',
                data: { canton_id: canton_id },
                success: function(response) {
                    $('#parroquia').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar parroquias:', error);
                }
            });
        } else {
            $('#parroquia').html('<option value="">Seleccione...</option>');
        }
    });
});
</script>
