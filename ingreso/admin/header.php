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

// Obtener las carreras de la tabla carrera
$carreras = [];
$sql_carreras = "SELECT idCarrera, nombreCarrera FROM carrera";
$resultado_carreras = mysqli_query($con, $sql_carreras);
if ($resultado_carreras && mysqli_num_rows($resultado_carreras) > 0) {
    $carreras = mysqli_fetch_all($resultado_carreras, MYSQLI_ASSOC);
}

// Obtener los aplicadores de la tabla aplicadores
$aplicadores = [];
$sql_aplicadores = "SELECT idAplicador, nombreAplicador FROM aplicadores";
$resultado_aplicadores = mysqli_query($con, $sql_aplicadores);
if ($resultado_aplicadores && mysqli_num_rows($resultado_aplicadores) > 0) {
    $aplicadores = mysqli_fetch_all($resultado_aplicadores, MYSQLI_ASSOC);
}

// Manejo del formulario para agregar un nuevo registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nombre = isset($_POST['nombre_completo']) ? trim($_POST['nombre_completo']) : null;
    $edad = isset($_POST['edad']) ? intval($_POST['edad']) : null;
    $carrera_interes = isset($_POST['idCarrera']) ? intval($_POST['idCarrera']) : null;
    $idAplicador = isset($_POST['idAplicador']) ? intval($_POST['idAplicador']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $clave = isset($_POST['clave']) ? password_hash(trim($_POST['clave']), PASSWORD_DEFAULT) : null; // Encriptar la clave
    $usuario = isset($_POST['tipoUsuario']) ? trim($_POST['tipoUsuario']) : null;
    $fecha_registro = date('Y-m-d H:i:s');

    // Insertar en la base de datos
    $stmt = $con->prepare("INSERT INTO personas (nombre_completo, edad, correo, clave, idCarrera, idAplicador, tipoUsuario, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissiiss", $nombre, $edad, $correo, $clave, $carrera_interes, $idAplicador, $usuario, $fecha_registro);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error al insertar en la base de datos: " . $stmt->error;
    }
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de control</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoUsuarioSelect = document.getElementById('tipoUsuario');
            const camposADesactivar = ['idCarrera', 'idAplicador'];

            tipoUsuarioSelect.addEventListener('change', function () {
                const isDisabled = ['Administrador', 'Super admin'].includes(tipoUsuarioSelect.value);

                camposADesactivar.forEach(campoId => {
                    const campo = document.getElementById(campoId);
                    if (campo) {
                        campo.disabled = isDisabled;
                        if (isDisabled) {
                            campo.value = ''; // Opcional: Limpia el valor si se deshabilita
                        }
                    }
                });
            });
        });
    </script>
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
                    <li class="nav-item"><a class="nav-link" href="carrera.php">Carreras</a></li>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="evaluaciones.php">Evaluaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="Preguntas.php">Preguntas</a></li>
                    <li class="nav-item"><a class="nav-link" href="aplicadores.php">Aplicadores</a></li>
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
                    <th>Correo</th>
                    <th>Carrera de Interés</th>
                    <th>Aplicador</th>
                    <th>Tipo de Usuario</th>
                    <th>Fecha de Registro</th>
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
                        echo "<td>{$row['idPersona']}</td>";
                        echo "<td>{$row['nombre_completo']}</td>";
                        echo "<td>{$row['edad']}</td>";
                        echo "<td>{$row['correo']}</td>";
                        echo "<td>" . htmlspecialchars($row['nombreCarrera'] ?: 'Sin asignar') . "</td>";
                        echo "<td>{$row['idAplicador']}</td>";
                        echo "<td>{$row['tipoUsuario']}</td>";
                        echo "<td>{$row['fecha_registro']}</td>";
                        echo "<td>
                                <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['idPersona']}'>Editar</button>
                                <a href='?delete={$row['idPersona']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";

                        // Modal de edición
                        echo "
                        <div class='modal fade' id='editModal{$row['idPersona']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <form method='post' action=''>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='editModalLabel'>Editar Registro</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <input type='hidden' name='action' value='edit'>
                                            <input type='hidden' name='idPersona' value='{$row['idPersona']}'>
                                            <div class='mb-3'>
                                                <label for='nombre_completo' class='form-label'>Nombre Completo</label>
                                                <input type='text' class='form-control' name='nombre_completo' value='{$row['nombre_completo']}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='edad' class='form-label'>Edad</label>
                                                <input type='number' class='form-control' name='edad' value='{$row['edad']}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='correo' class='form-label'>Correo</label>
                                                <input type='email' class='form-control' name='correo' value='{$row['correo']}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='idCarrera' class='form-label'>Carrera de Interés</label>
                                                <select class='form-control' name='idCarrera' required>
                                                    <option value='' disabled>Seleccione una carrera</option>";
                                                    foreach ($carreras as $carrera) {
                                                        $selected = $carrera['idCarrera'] == $row['idCarrera'] ? 'selected' : '';
                                                        echo "<option value='{$carrera['idCarrera']}' $selected>{$carrera['nombreCarrera']}</option>";
                                                    }
                        echo "              </select>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='idAplicador' class='form-label'>Aplicador</label>
                                                <select class='form-control' name='idAplicador' required>
                                                    <option value='' disabled>Seleccione un aplicador</option>";
                                                    foreach ($aplicadores as $aplicador) {
                                                        $selected = $aplicador['idAplicador'] == $row['idAplicador'] ? 'selected' : '';
                                                        echo "<option value='{$aplicador['idAplicador']}' $selected>{$aplicador['nombreAplicador']}</option>";
                                                    }
                        echo "              </select>
                                            </div>
                                            <div class='mb-3'>
                                                <label for='tipoUsuario' class='form-label'>Tipo de Usuario</label>
                                                <select class='form-control' name='tipoUsuario' required>
                                                    <option value='Administrador' " . ($row['tipoUsuario'] == 'Administrador' ? 'selected' : '') . ">Administrador</option>
                                                    <option value='Super admin' " . ($row['tipoUsuario'] == 'Super admin' ? 'selected' : '') . ">Super admin</option>
                                                    <option value='Usuario' " . ($row['tipoUsuario'] == 'Usuario' ? 'selected' : '') . ">Usuario</option>
                                                    <option value='Invitado' " . ($row['tipoUsuario'] == 'Invitado' ? 'selected' : '') . ">Invitado</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                            <button type='submit' class='btn btn-primary'>Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay registros disponibles</td></tr>";
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
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>
                    <div class="mb-3">
                        <label for="idCarrera" class="form-label">Carrera de Interés</label>
                        <select class="form-control" id="idCarrera" name="idCarrera" required>
                            <option value="" disabled selected>Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?php echo htmlspecialchars($carrera['idCarrera']); ?>">
                                    <?php echo htmlspecialchars($carrera['nombreCarrera']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idAplicador" class="form-label">Aplicador</label>
                        <select class="form-control" id="idAplicador" name="idAplicador" required>
                            <option value="" disabled selected>Seleccione un aplicador</option>
                            <?php foreach ($aplicadores as $aplicador): ?>
                                <option value="<?php echo htmlspecialchars($aplicador['idAplicador']); ?>">
                                    <?php echo htmlspecialchars($aplicador['nombreAplicador']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipoUsuario" class="form-label">Tipo de Usuario</label>
                        <select class="form-control" id="tipoUsuario" name="tipoUsuario" required>
                            <option value="" disabled selected>Seleccione un tipo de usuario</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Super admin">Super admin</option>
                            <option value="Usuario">Usuario</option>
                            <option value="Invitado">Invitado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                        <input type="text" class="form-control" id="fecha_registro" name="fecha_registro" value="<?php echo date('Y-m-d'); ?>" readonly>
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
