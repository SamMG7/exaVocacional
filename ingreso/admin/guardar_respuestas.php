<?php
include_once("../conf.php");

// Conectar a la base de datos
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener las respuestas del formulario
    $respuestas = $_POST['escala'] ?? [];

    foreach ($respuestas as $idPregunta => $puntaje) {
        $idPregunta = intval($idPregunta);
        $puntaje = intval($puntaje);

        // Guardar la respuesta en la tabla "respuestas"
        $sql = "INSERT INTO respuestas (idPregunta, puntaje) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $idPregunta, $puntaje);

        if (!mysqli_stmt_execute($stmt)) {
            echo "Error al guardar respuesta: " . mysqli_error($con);
        }
    }

    // Redirigir con mensaje de éxito
    header("Location: vista_preguntas.php?msj=success");
    exit;
}
?>
