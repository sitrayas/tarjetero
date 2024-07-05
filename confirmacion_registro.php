<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Confirmación de Registro</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('barra_lateral.php'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1 class="text-center">Confirmación de Registro</h1></div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        Paciente registrado correctamente.
                    </div>
                    <div class="text-center">
                        <a href="registrar_hc.php" class="btn btn-primary">Registrar otro paciente</a>
                        <a href="index.php" class="btn btn-danger">Salir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
