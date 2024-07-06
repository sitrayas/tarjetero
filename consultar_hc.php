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

$mensaje_busqueda = ''; // Variable para almacenar el mensaje de búsqueda
$resultados = []; // Array para almacenar los resultados de la búsqueda

// Procesar la búsqueda de paciente
if(isset($_POST['btnbuscar'])) {
    $tipo_busqueda = $_POST['tipo_busqueda'];
    $termino_buscar = $_POST['termino_buscar'];

    // Consulta según el tipo de búsqueda seleccionado
    switch ($tipo_busqueda) {
        case 'numero_hc':
            $query_busqueda = "SELECT * FROM Pacientes WHERE numero_historia_clinica = '$termino_buscar'";
            break;
        case 'apellido':
            $query_busqueda = "SELECT * FROM Pacientes WHERE apellido_paterno LIKE '%$termino_buscar%' OR apellido_materno LIKE '%$termino_buscar%'";
            break;
        case 'cedula':
            $query_busqueda = "SELECT * FROM Pacientes WHERE cedula = '$termino_buscar'";
            break;
        case 'fecha_nacimiento':
            // Suponiendo que $termino_buscar sea una fecha válida en formato yyyy-mm-dd
            $query_busqueda = "SELECT * FROM Pacientes WHERE fecha_de_nacimiento = '$termino_buscar'";
            break;
        case 'fecha_registro':
            // Suponiendo que $termino_buscar sea una fecha válida en formato yyyy-mm-dd
            $query_busqueda = "SELECT * FROM Pacientes WHERE fecha_de_ingreso = '$termino_buscar'";
            break;
        default:
            $mensaje_busqueda = 'Seleccione un tipo de búsqueda válido.';
            break;
    }

    if (isset($query_busqueda)) {
        $result_busqueda = mysqli_query($conn, $query_busqueda);

        if(mysqli_num_rows($result_busqueda) > 0) {
            while($row = mysqli_fetch_assoc($result_busqueda)) {
                $resultados[] = $row;
            }
        } else {
            $mensaje_busqueda = 'No se encontraron resultados para el término ingresado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Historia Clínica</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        /* Estilos adicionales si los necesitas */
    </style>
</head>
<body>
    <?php include('barra_lateral.php'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Consultar Historia Clínica</h1>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label>Seleccione el tipo de búsqueda:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_busqueda" id="busqueda_hc" value="numero_hc" checked>
                                    <label class="form-check-label" for="busqueda_hc">Número de Historia Clínica</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_busqueda" id="busqueda_apellido" value="apellido">
                                    <label class="form-check-label" for="busqueda_apellido">Apellido</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_busqueda" id="busqueda_cedula" value="cedula">
                                    <label class="form-check-label" for="busqueda_cedula">Cédula</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_busqueda" id="busqueda_fecha_nacimiento" value="fecha_nacimiento">
                                    <label class="form-check-label" for="busqueda_fecha_nacimiento">Fecha de Nacimiento</label>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="termino_buscar">Ingrese el término de búsqueda:</label>
                                <div class="input-group">
                                    <input type="text" id="termino_buscar" name="termino_buscar" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" name="btnbuscar">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <?php if (!empty($mensaje_busqueda)): ?>
                            <div class="alert alert-warning" role="alert">
                                <?php echo $mensaje_busqueda; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($resultados)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Número HC</th>
                                            <th>Apellido Paterno</th>
                                            <th>Apellido Materno</th>
                                            <th>Primer Nombre</th>
                                            <th>Segundo Nombre</th>
                                            <th>Nombre del padre</th>
                                            <th>Nombre de la madre</th>
                                            <th>Fecha de Nacimiento</th>
                                            <th>Cédula</th>
                                            <th>Fecha de Ingreso</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resultados as $resultado): ?>
                                            <tr>
                                                <td><?php echo $resultado['numero_historia_clinica']; ?></td>
                                                <td><?php echo $resultado['apellido_paterno']; ?></td>
                                                <td><?php echo $resultado['apellido_materno']; ?></td>
                                               
                                                <td><?php echo $resultado['primer_nombre']; ?></td>
                                                <td><?php echo $resultado['segundo_nombre']; ?></td>
                                                
                                                <td><?php echo $resultado['nombre_del_padre']; ?></td>
                                                <td><?php echo $resultado['nombre_de_la_madre']; ?></td>
                                                <td><?php echo $resultado['fecha_de_nacimiento']; ?></td>
                                                <td><?php echo $resultado['cedula']; ?></td>
                                                <td><?php echo $resultado['fecha_de_ingreso']; ?></td>
                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
    <script>
        // Inicializar datepicker para los campos de fecha
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                language: 'es',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
</body>
</html>
