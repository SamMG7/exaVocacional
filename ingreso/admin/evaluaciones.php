<?php
// Incluir la configuración y conectar con la base de datos
include_once("../conf.php");
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
$msj = "";

// Consultar usuarios (tabla personas)
$usuarios = mysqli_query($con, "SELECT idPersona, nombre_completo FROM personas");

// Consultar carreras
$carreras = mysqli_query($con, "SELECT idCarrera, nombreCarrera FROM carrera");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Evaluación</title>
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
        <!-- Botón para abrir el modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarEvaluacion">Agregar Evaluación</button>

        <!-- Modal -->
        <div class="modal fade" id="modalAgregarEvaluacion" tabindex="-1" aria-labelledby="modalAgregarEvaluacionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarEvaluacionLabel">Nueva Evaluación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="guardar_evaluacion.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="idUsuario" class="form-label">Usuario</label>
                                <select id="idUsuario" name="idUsuario" class="form-select" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php while ($usuario = mysqli_fetch_assoc($usuarios)) : ?>
                                        <option value="<?= $usuario['idPersona'] ?>"><?= $usuario['nombre_completo'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="idCarrera" class="form-label">Carrera</label>
                                <select id="idCarrera" name="idCarrera" class="form-select" required>
                                    <option value="">Seleccione una carrera</option>
                                    <?php while ($carrera = mysqli_fetch_assoc($carreras)) : ?>
                                        <option value="<?= $carrera['idCarrera'] ?>"><?= $carrera['nombreCarrera'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="idExamen" class="form-label">Examen</label>
                                <input type="text" id="idExamen" name="idExamen" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="fechaExamen" class="form-label">Fecha de asignación</label>
                                <input type="datetime-local" id="fechaExamen" name="fechaExamen" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de evaluaciones -->
        <!-- Tabla de evaluaciones -->
<table class="table">
    <thead class="bg-info">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Usuario</th>
            <th scope="col">Carrera</th>
            <th scope="col">Examen</th>
            <th scope="col">Fecha de asignación</th>
            <th scope="col">Examen terminado</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Nueva consulta con JOIN para obtener los nombres de usuario y carrera
        $sql = "
            SELECT 
                e.idEvaluacion, 
                p.nombre_completo, 
                c.nombreCarrera, 
                e.idExamen, 
                e.fecha_examen, 
                e.examenCompleto
            FROM evaluaciones e
            JOIN personas p ON e.idPersona = p.idPersona
            JOIN carrera c ON e.idCarrera = c.idCarrera
        ";
        $resultado = mysqli_query($con, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $row['idEvaluacion'] . "</td>";
                echo "<td>" . $row['nombre_completo'] . "</td>";
                echo "<td>" . $row['nombreCarrera'] . "</td>";
                echo "<td>" . $row['idExamen'] . "</td>";
                echo "<td>" . $row['fecha_examen'] . "</td>";
                echo "<td>" . ($row['examenCompleto'] ? 'Sí' : 'No') . "</td>";
                echo "<td>";
                echo "<a href='descargarExamen.php?id=" . $row['idEvaluacion'] . "'><i class='fas fa-download'></i></a> ";
                echo "<a href='eliminar_evaluacion.php?id=" . $row['idEvaluacion'] . "'><i class='fas fa-trash-alt'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay evaluaciones registradas</td></tr>";
        }
        ?>
    </tbody>
</table>
 
    </div>
</main>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
