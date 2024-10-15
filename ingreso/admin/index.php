<?php
  include_once("../conf.php");
  // Crear la conexión
  $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
  $msj="";
 

// Verificar si se ha enviado el formulario
if (isset($_POST['username'])) {
    // Recibir datos del formulario
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5(mysqli_real_escape_string($con, $_POST['password']));

    // Consulta para verificar usuario y contraseña juntos
    $sql = "SELECT * FROM usuarios WHERE correo = '$username' AND clave = '$password' AND activo= 'si'";
    //echo $sql;
    $result = mysqli_query($con, $sql);

    // Verificar si el usuario y la contraseña coinciden
    if (mysqli_num_rows($result) > 0) {
        // Iniciar sesión y redirigir
        session_start();
        $_SESSION['username'] = $username;
        
        // Redirigir al dashboard o página principal
        header("Location: bienvenida.php");
        mysqli_close($con);
        exit();
    } else {
        $msj= "Usuario o contraseña incorrectos.";
    }
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Inicio de sesión</title>

    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="../css/bootstrap.min.css" rel="stylesheet">

<meta name="theme-color" content="#712cf9">


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

    
    <!-- Custom styles for this template -->
    <link href="../css/sign-in.css" rel="stylesheet">
  </head>
  <body class="d-flex align-items-center py-4 bg-body-tertiary">
    
    
<main class="form-signin w-100 m-auto">
  <form action="index.php" method="POST">
    <img class="mb-4" src="../img/R.png" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
      <label for="floatingPassword">Password</label>
    </div>
    
    <?php
    if($msj!=""){

    ?>
    <div class="alert alert-warning" role="alert">
      <?php
      echo $msj;
      ?>
</div>
<?php
    }
    ?>
    <input class="btn btn-primary w-100 py-2" type="submit" name="ingresar">
    <p class="mt-5 mb-3 text-body-secondary">&copy; <?php echo date("Y"); ?></p>
  </form>
</main>
<script src="../js/bootstrap.bundle.min.js"></script>

    </body>
</html>

<?php
  mysqli_close($con);
?>