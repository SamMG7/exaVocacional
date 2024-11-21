<?php
// Iniciar sesión y verificar que el usuario esté autenticado
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include_once("../conf.php");
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener las carreras de la tabla `carrera`
$carreras = [];
$sql_carreras = "SELECT idCarrera, nombreCarrera FROM carrera";
$resultado_carreras = mysqli_query($con, $sql_carreras);
if ($resultado_carreras && mysqli_num_rows($resultado_carreras) > 0) {
    $carreras = mysqli_fetch_all($resultado_carreras, MYSQLI_ASSOC);
}

// Obtener los aplicadores de la tabla `aplicadores`
$aplicadores = [];
$sql_aplicadores = "SELECT idAplicador, nombreAplicador FROM aplicadores";
$resultado_aplicadores = mysqli_query($con, $sql_aplicadores);
if ($resultado_aplicadores && mysqli_num_rows($resultado_aplicadores) > 0) {
    $aplicadores = mysqli_fetch_all($resultado_aplicadores, MYSQLI_ASSOC);
}

// Manejo del formulario para agregar un nuevo registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombre_completo']) ? mysqli_real_escape_string($con, $_POST['nombre_completo']) : null;
    $edad = isset($_POST['edad']) ? intval($_POST['edad']) : null;
    $carrera_interes = isset($_POST['idCarrera']) ? intval($_POST['idCarrera']) : null;
    $idAplicador = isset($_POST['idAplicador']) ? intval($_POST['idAplicador']) : null;
    $fecha_registro = date('Y-m-d');

    // Validar campos obligatorios
    if (!$nombre || !$edad || !$carrera_interes || !$idAplicador) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validar carrera
    $carrera_check = mysqli_prepare($con, "SELECT idCarrera FROM carrera WHERE idCarrera = ?");
    mysqli_stmt_bind_param($carrera_check, 'i', $carrera_interes);
    mysqli_stmt_execute($carrera_check);
    if (mysqli_stmt_get_result($carrera_check)->num_rows === 0) {
        die("Error: La carrera seleccionada no existe.");
    }

    // Validar aplicador
    $aplicador_check = mysqli_prepare($con, "SELECT idAplicador FROM aplicadores WHERE idAplicador = ?");
    mysqli_stmt_bind_param($aplicador_check, 'i', $idAplicador);
    mysqli_stmt_execute($aplicador_check);
    if (mysqli_stmt_get_result($aplicador_check)->num_rows === 0) {
        die("Error: El aplicador seleccionado no existe.");
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO personas (nombre_completo, edad, idCarrera, idAplicador, fecha_registro)
            VALUES ('$nombre', $edad, $carrera_interes, $idAplicador, '$fecha_registro')";

    if (mysqli_query($con, $sql)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error al insertar en la base de datos: " . mysqli_error($con);
    }
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Panel de control</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<main>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel de control</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample03">
                <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="evaluaciones.php">Evaluaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="Preguntas.php">Preguntas</a></li>
                    <li class="nav-item"><a class="nav-link" href="salir.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Gestión de Personas</h1>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Nuevo Registro</button>

        <table class="table">
            <thead class="bg-info">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Carrera de Interés</th>
                    <th>idAplicador</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT personas.*, carrera.nombreCarrera AS nombreCarrera 
                        FROM personas 
                        LEFT JOIN carrera ON personas.idCarrera = carrera.idCarrera";
                $resultado = mysqli_query($con, $sql);
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['nombre_completo']}</td>";
                        echo "<td>{$row['edad']}</td>";
                        echo "<td>" . htmlspecialchars($row['nombreCarrera'] ?: 'Sin asignar') . "</td>";
                        echo "<td>{$row['idAplicador']}</td>";
                        echo "<td>{$row['fecha_registro']}</td>";
                        echo "<td>";
                        echo "<a href='modificar.php?id={$row['id']}'><i class='fas fa-edit'></i></a> ";
                        echo "<a href='eliminar.php?id={$row['id']}'><i class='fas fa-trash-alt'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay registros disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal para agregar nuevo registro -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Agregar Nuevo Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edad" name="edad" required>
                    </div>
                    <div class="mb-3">
                        <label for="carrera_interes" class="form-label">Carrera de Interés</label>
                        <select class="form-control" id="carrera_interes" name="carrera_interes" required>
                            <option value="" disabled selected>Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?php echo htmlspecialchars($carrera['idCarrera']); ?>">
                                    <?php echo htmlspecialchars($carrera['nombreCarrera']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_aplicador" class="form-label">Nombre del Aplicador</label>
                        <select class="form-control" id="nombre_aplicador" name="idAplicador" required>
                            <option value="" disabled selected>Seleccione un aplicador</option>
                            <?php foreach ($aplicadores as $aplicador): ?>
                                <option value="<?php echo htmlspecialchars($aplicador['idAplicador']); ?>">
                                    <?php echo htmlspecialchars($aplicador['nombreAplicador']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
