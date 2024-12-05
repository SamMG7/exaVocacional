<?php
include_once("../conf.php");

// Conectar con la base de datos
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se recibe el ID por GET para cargar los datos existentes
if (isset($_GET['id'])) {
    $idPersona = intval($_GET['id']);
    $sql = "SELECT * FROM personas WHERE idPersona = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idPersona);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $persona = mysqli_fetch_assoc($resultado);
    } else {
        die("No se encontró el registro.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si el formulario de edición se envía
    $idPersona = intval($_POST['idPersona']);
    $nombre = trim($_POST['nombre_completo']);
    $edad = intval($_POST['edad']);
    $correo= trim($_POST['correo']);
    $clave= trim($_POST['clave']);
    $usuario= trim(string: $_POST['tipoUsuario']);
    $idCarrera = intval($_POST['idCarrera']);
    $idAplicador = intval($_POST['idAplicador']);

    // Validar campos obligatorios
    if (!empty($nombre) && $edad > 0 && $idCarrera && $idAplicador) {
        $sql = "UPDATE personas 
                SET nombre_completo = ?, edad = ?, idCarrera = ?, idAplicador = ? 
                WHERE idPersona = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "siiii", $nombre, $edad, $idCarrera, $idAplicador, $idPersona);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: header.php?msj=updated");
            exit;
        } else {
            echo "Error al actualizar el registro: " . mysqli_error($con);
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    die("Acceso no permitido.");
}

// Cargar listas de carreras y aplicadores
$carreras = mysqli_query($con, "SELECT idCarrera, nombreCarrera FROM carrera");
$aplicadores = mysqli_query($con, "SELECT idAplicador, nombreAplicador FROM aplicadores");

mysqli_close($con);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Persona</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Editar Persona</h1>
    <form method="POST" action="modificar_persona.php">
        <input type="hidden" name="idPersona" value="<?= $persona['idPersona'] ?>">
        <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= htmlspecialchars($persona['nombre_completo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="edad" class="form-label">Edad</label>
            <input type="number" class="form-control" id="edad" name="edad" value="<?= $persona['edad'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($persona['correo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave</label>
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Dejar en blanco para no cambiar">
        </div>
        <div class="mb-3">
            <label for="idCarrera" class="form-label">Carrera de Interés</label>
            <select class="form-control" id="idCarrera" name="idCarrera" required>
                <?php while ($carrera = mysqli_fetch_assoc($carreras)) : ?>
                    <option value="<?= $carrera['idCarrera'] ?>" <?= $carrera['idCarrera'] == $persona['idCarrera'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($carrera['nombreCarrera']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="idAplicador" class="form-label">Aplicador</label>
            <select class="form-control" id="idAplicador" name="idAplicador" required>
                <?php while ($aplicador = mysqli_fetch_assoc($aplicadores)) : ?>
                    <option value="<?= $aplicador['idAplicador'] ?>" <?= $aplicador['idAplicador'] == $persona['idAplicador'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($aplicador['nombreAplicador']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
                                    <label for="tipoUsuario" class="form-label">Tipo de usuario</label>
                                    <select class="form-control" name="tipoUsuario" required>
                                        <option value="Super Admin" <?php echo $usuario['tipoUsuario'] == 'Super Admin' ? 'selected' : ''; ?>>Super Admin</option>
                                        <option value="Admin" <?php echo $usuario['tipoUsuario'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="User" <?php echo $usuario['tipoUsuario'] == 'User' ? 'selected' : ''; ?>>User</option>
                                    </select>
                                </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="header.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
