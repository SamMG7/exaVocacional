<?php
include_once("../conf.php");
include_once("../conf.php");

// Crear la conexión 
$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
$msj = "";

?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="0">
    <title>Panel de control</title>

    

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Favicons -->
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
    <link href="../css/navbars.css" rel="stylesheet">
  </head>
  <body>
 

<main>
 
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark" aria-label="Third navbar example">
    <div class="container-fluid">
      
      <a class="navbar-brand" href="#">Panel de control</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample03">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="evaluaciones.php">Evaluaciones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="examnen">Examen</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="salir.php">Salir</a>
          </li>
        </ul>
        
      </div>
    </div>
  </nav>

  <div>
  <table class="table">
  <thead class="bg-info">
    <tr>
      <th scope="col">id</th>
      <th scope="col">Id Usuario</th>
      <th scope="col">CARRERA</th>
      <th scope="col">IdUsuario</th>
      <th scope="col">Tiempo de examen</th>
      <th scope="col"></th>

    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM evaluaciones";
    if($resultado=mysqli_query($con, $sql)){

    
    if(mysqli_num_rows($resultado) >0){
      while($row = mysqli_fetch_assoc($resultado)){
        echo "<tr>";
                  echo "<td>" . $row['idUsuario'] . "</td>";
                  echo "<td>" . $row['idPersona'] . "</td>";
                  echo "<td>" . $row['idCarrera'] . "</td>";
                  echo "<td>" . $row['idUsuario'] . "</td>";
                  echo "<td>" . $row['examenCompleto'] . "</td>";
                  echo "<td>" . $row['tiempoReso'] . "</td>";
                  echo "<td>";
                  echo "<a href='modificar.php?id=" . $row['idUsuario'] . "'><i class='fas fa-edit'></i></a> ";
                  echo "<a href='eliminar.php?id=" . $row['idUsuario'] . "'><i class='fas fa-trash-alt'></i></a>";
                

      }
    }else{
      echo "<tr><td colspan= '6>No hay usuarios</td></tr>";
    }
  }
    ?>
    
  </tbody>
</table>
  </div>
