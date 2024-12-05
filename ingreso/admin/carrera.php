<?php
include_once("../conf.php");

// Conectar a la base de datos usando mysqli
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar que la conexión esté establecida
if (!$con) {
    die("Error: No se pudo establecer la conexión con la base de datos. " . mysqli_connect_error());
}

// Crear una nueva carrera
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $nombre = mysqli_real_escape_string($con, $_POST['nombreCarrera']);
    $stmt = $con->prepare("INSERT INTO carrera (nombreCarrera) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    if ($stmt->execute()) {
        header("Location: carrera.php");
        exit();
    } else {
        echo "Error al crear la carrera: " . $stmt->error;
    }
    $stmt->close();
}

// Leer todas las carreras
$query = "SELECT * FROM carrera";
$result = mysqli_query($con, $query);

if ($result) {
    $carreras = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else {
    echo "Error al leer las carreras: " . mysqli_error($con);
}

// Actualizar una carrera
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['idCarrera']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombreCarrera']);
    $stmt = $con->prepare("UPDATE carrera SET nombreCarrera = ? WHERE idCarrera = ?");
    $stmt->bind_param("si", $nombre, $id);
    if ($stmt->execute()) {
        header("Location: carrera.php");
        exit();
    } else {
        echo "Error al actualizar la carrera: " . $stmt->error;
    }
    $stmt->close();
}

// Eliminar una carrera
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM carrera WHERE idCarrera = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: carrera.php");
        exit();
    } else {
        echo "Error al eliminar la carrera: " . $stmt->error;
    }
    $stmt->close();
}

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Gestión de Carreras</title>
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
                    <li class="nav-item"><a class="nav-link active" href="header.php">Inicio</a></li>
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
        <h1 class="mb-4">Gestión de Carreras</h1>

        <!-- Formulario para crear nueva carrera -->
        <form action="carrera.php" method="post" class="mb-4">
            <input type="hidden" name="action" value="create">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="nombreCarrera" placeholder="Nombre de la Carrera" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Agregar Carrera</button>
                </div>
            </div>
        </form>

        <!-- Tabla para mostrar todas las carreras -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carreras as $carrera): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($carrera['idCarrera']); ?></td>
                        <td><?php echo htmlspecialchars($carrera['nombreCarrera']); ?></td>
                        <td>
                            <a href="carrera.php?edit=<?php echo $carrera['idCarrera']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="carrera.php?delete=<?php echo $carrera['idCarrera']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta carrera?');">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulario para actualizar carrera -->
        <?php if (isset($_GET['edit'])):
            $id = intval($_GET['edit']);
            $stmt = $con->prepare("SELECT * FROM carrera WHERE idCarrera = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0):
                $carrera = $result->fetch_assoc();
        ?>
            <h2 class="mt-4">Editar Carrera</h2>
            <form action="carrera.php" method="post" class="mt-3">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="idCarrera" value="<?php echo htmlspecialchars($carrera['idCarrera']); ?>">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="nombreCarrera" value="<?php echo htmlspecialchars($carrera['nombreCarrera']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">Actualizar Carrera</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p class="text-danger">Carrera no encontrada.</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
