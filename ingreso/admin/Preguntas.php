<?php
  include_once("../conf.php");

  // Crear la conexión 
  $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

  // Verificar que la conexión esté establecida
  if (!$con) {
    die("Error: No se pudo establecer la conexión con la base de datos. " . mysqli_connect_error());
  }

  /*Agregar una nueva pregunta*/
  if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $reactivo = mysqli_real_escape_string($con, $_POST['reactivo']);
    $escala = mysqli_real_escape_string($con, $_POST['escala']);
    $seccion = mysqli_real_escape_string($con, $_POST['seccion']);

    $stmt = $con->prepare("INSERT INTO preguntas (reactivo, escala, seccion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $reactivo, $escala, $seccion);

    if ($stmt->execute()) {
        header("Location: Preguntas.php");
        exit();
    } else {
        echo "Error al guardar la pregunta: " . $stmt->error;
    }
    $stmt->close();
  }

  /*Leer todas las preguntas*/
  $query = "SELECT * FROM preguntas";
  $result = mysqli_query($con, $query);

  if ($result) {
      $preguntas = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
  } else {
      echo "Error al leer las preguntas: " . mysqli_error($con);
  }

  /*Eliminar pregunta*/
  if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM preguntas WHERE idPregunta = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: Preguntas.php");
        exit();
    } else {
        echo "Error al eliminar la pregunta: " . $stmt->error;
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
                  <select name="seccion" class="form-control" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="Preferencias">Prefe</option>
                    <option value="Fisico-mate">FM</option>
                    <option value="Biologica">B</option>
                    <option value="Quimica">Q</option>
                    <option value="Administrativa">A</option>
                    <option value="Social">S</option>
                    <option value="Humanidades">H</option>
                  </select>
              </div>
              <br>
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
          <?php foreach ($preguntas as $pregunta): ?>
            <tr>
              <td><?php echo htmlspecialchars($pregunta['idPregunta']); ?></td>
              <td><?php echo htmlspecialchars($pregunta['reactivo']); ?></td>
              <td><?php echo htmlspecialchars($pregunta['escala']); ?></td>
              <td><?php echo htmlspecialchars($pregunta['seccion']); ?></td>
              <td>
                <a href="#" class="btn btn-warning btn-sm">
                  <i class='fas fa-edit'></i>
                </a>
                <a href="Preguntas.php?delete=<?php echo $pregunta['idPregunta']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta pregunta?');">
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
