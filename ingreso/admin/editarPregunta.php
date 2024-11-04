<?php
    include "../conf.php";

    $id =$_GET["idPregunta"];
    $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
    $sql = $con -> query("SELECT * FROM preguntas WHERE idPregunta=$id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Pregunta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <form class="col-4 p-3 m-auto" method="POST">
        <h5 class="text-center alert alert-secondary">Modificar pregunta</h5>
        <input type="hidden" name="id" value="<?= $_GET["idPregunta"] ?>">
        <?php
            include "modificar_pregunta.php";
            while($datos=$sql -> fetch_object()){ ?>
                <div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Reactivo</label>
                    <input type="text" class="form-control" name="reactivo" value="<?= $datos->reactivo ?>">
                </div>
                <div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Escala</label>
                    <input type="text" class="form-control" name="escala" value="<?= $datos->escala ?>">
                </div>
                <div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Sección</label>
                    <input type="text" class="form-control" name="seccion" value="<?= $datos->seccion ?>">
                </div>            
            <?php
            }
        ?>
        <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">Actualizar</button>
    </form>    
</body>
</html>