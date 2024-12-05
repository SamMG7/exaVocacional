<?php
include_once("../conf.php");

// Conectar a la base de datos
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta para obtener preguntas de la sección "prefe"
$sql = "SELECT idPregunta, reactivo FROM preguntas WHERE seccion = 'prefe'";
$resultado = mysqli_query($con, $sql);

// Verificar si hay preguntas disponibles
$preguntas = [];
if ($resultado && mysqli_num_rows($resultado) > 0) {
    $preguntas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vista de Preguntas</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        // Script para sumar el puntaje automáticamente
        function actualizarPuntaje() {
            let puntajeTotal = 0;
            const selects = document.querySelectorAll('.escala-select');

            selects.forEach(select => {
                puntajeTotal += parseInt(select.value) || 0;
            });

            document.getElementById('puntajeTotal').innerText = puntajeTotal;
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <h1>Preguntas de la Sección "Prefe"</h1>
    <form method="POST" action="guardar_respuestas.php">
        <table class="table">
            <thead class="bg-info">
                <tr>
                    <th>#</th>
                    <th>Pregunta</th>
                    <th>Escala</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($preguntas)): ?>
                    <?php foreach ($preguntas as $index => $pregunta): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($pregunta['reactivo']) ?></td>
                            <td>
                                <select class="form-select escala-select" name="escala[<?= $pregunta['idPregunta'] ?>]" onchange="actualizarPuntaje()" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <?php
                                    // Asumimos que el campo "escala" tiene valores separados por comas, por ejemplo: "1,2,3,4,5"
                                    $opciones = explode(',', $pregunta['escala']);
                                    foreach ($opciones as $opcion): ?>
                                        <option value="<?= intval($opcion) ?>"><?= intval($opcion) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No hay preguntas disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <h3>Puntaje Total: <span id="puntajeTotal">0</span></h3>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Respuestas</button>
    </form>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
