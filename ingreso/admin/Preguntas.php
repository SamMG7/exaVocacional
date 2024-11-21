<?php
  include_once("../conf.php");

  // Crear la conexión 
  $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

  /*Eliminar pregunta*/
  if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    // Verificar que la conexión a la base de datos esté establecida
    if ($con) {
      // Preparar la consulta para evitar inyección SQL
      $stmt = $con->prepare("DELETE FROM preguntas WHERE idPregunta = ?");
      // Verificar si la preparación de la consulta fue exitosa
      if ($stmt) {
        // Vincular el parámetro a la consulta
        $stmt->bind_param("i", $id); // "i" indica que es un entero
        // Ejecutar la consulta
        if ($stmt->execute()) {
          echo '<div>Pregunta eliminada correctamente</div>';
          // Redireccionar a una página de confirmación o al listado principal
          header("Location: Preguntas.php?");
          exit();
        } else {
            echo '<div>Error al eliminar pregunta: ' . $stmt->error . '</div>';
          }
        // Cerrar la declaración preparada
        $stmt->close();
      } else {
          echo '<div>Error al preparar la consulta: ' . $con->error . '</div>';
        }
      } else {
          echo '<div>Error de conexión a la base de datos</div>';
        }
  } else {
    echo '<div>ID no proporcionado</div>';
    }
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="theme-color" content="#712cf9">
    <title>Concentrado de preguntas</title>  

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../css/navbars.css" rel="stylesheet">
    
  <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
  </style>  
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
      <h1 class="mb-4">Concentrado de preguntas</h1>

      <!-- Insertar pregunta -->
      <form action="Pregunta.php" method="post" class="mb-4">
          <input type="hidden" name="action" value="create">
          <div class="row">
              <div class="col-md-8">
                  <input type="text" class="form-control" name="reactivo" placeholder="Nueva pregunta" required>
                  <input type="number" class="form-control" name="escala" placeholder="Escala" required>
                  <input type="text" class="form-control" name="seccion" placeholder="Sección" required>
              </div>
              <div class="col-md-4">
                  <button type="submit" class="btn btn-primary w-100">Agregar Pregunta</button>
              </div>
          </div>
      </form>
  

      <table class="table">
        <thead class="bg-info">
          <tr>
            <th scope="col">Id pregunta</th>
            <th scope="col">Reactivo</th>
            <th scope="col">Escala</th>
            <th scope="col">Sección</th>
          </tr>
        </thead>
      
        <tbody>
          <?php foreach ($pregunta as $preguntas): ?>
            <tr>
              <td><?php echo htmlspecialchars($preguntas['idPregunta']); ?></td>
              <td><?php echo htmlspecialchars($preguntas['reactivo']); ?></td>
              <td><?php echo htmlspecialchars($preguntas['escala']); ?></td>
              <td><?php echo htmlspecialchars($preguntas['seccion']); ?></td>
              <td>
                <a href="#" class="btn btn-warning btn-sm">
                  <i class='fas fa-edit'></i>
                </a>
                <a href="Preguntas.php?delete=<?php echo $carrera['idCarrera']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta pregunta?');">
                  <i class='fas fa-trash-alt'></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
