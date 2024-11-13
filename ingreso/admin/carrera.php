<?php
include_once("../conf.php");
include_once("../conf.php");

// Crear una nueva carrera
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = $_POST['nombreCarrera'];
    $stmt = $pdo->prepare("INSERT INTO carrera (nombreCarrera) VALUES (:nombre)");
    $stmt->execute(['nombre' => $nombre]);
    header("Location: carrera.php");
    exit();
}

// Leer todas las carreras
$stmt = $pdo->query("SELECT * FROM carrera");
$carreras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Actualizar una carrera
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['idCarrera'];
    $nombre = $_POST['nombreCarrera'];
    $stmt = $pdo->prepare("UPDATE carrera SET nombreCarrera = :nombre WHERE idCarrera = :id");
    $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    header("Location: carrera.php");
    exit();
}

// Eliminar una carrera
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM carrera WHERE idCarrera = :id");
    $stmt->execute(['id' => $id]);
    header("Location: carrera.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Carreras</title>
</head>
<body>
    <h1>Gestión de Carreras</h1>

    <!-- Formulario para crear nueva carrera -->
    <form action="carrera.php" method="post">
        <input type="hidden" name="action" value="create">
        <label>Nombre de la Carrera:</label>
        <input type="text" name="nombreCarrera" required>
        <button type="submit">Agregar Carrera</button>
    </form>

    <!-- Tabla para mostrar todas las carreras -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($carreras as $carrera): ?>
            <tr>
                <td><?php echo $carrera['idCarrera']; ?></td>
                <td><?php echo $carrera['nombreCarrera']; ?></td>
                <td>
                    <a href="carrera.php?edit=<?php echo $carrera['idCarrera']; ?>">Editar</a>
                    <a href="carrera.php?delete=<?php echo $carrera['idCarrera']; ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Formulario para actualizar carrera -->
    <?php if (isset($_GET['edit'])):
        $id = $_GET['edit'];
        $stmt = $pdo->prepare("SELECT * FROM carrera WHERE idCarrera = :id");
        $stmt->execute(['id' => $id]);
        $carrera = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Editar Carrera</h2>
        <form action="carrera.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="idCarrera" value="<?php echo $carrera['idCarrera']; ?>">
            <label>Nombre de la Carrera:</label>
            <input type="text" name="nombreCarrera" value="<?php echo $carrera['nombreCarrera']; ?>" required>
            <button type="submit">Actualizar Carrera</button>
        </form>
    <?php endif; ?>
</body>
</html>
