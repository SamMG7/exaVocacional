<?php
    include "../conf.php";

    $id =$_GET["id"];
    $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
    $sql = $con -> query("SELECT * FROM usuarios WHERE id=$id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <form class="col-4 p-3 m-auto" method="POST">
        <h5 class="text-center alert alert-secondary">Modificar registro</h5>
        <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
        <?php
            include "modificar_registro.php";
            while($datos=$sql -> fetch_object()){ ?>
                <div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Correo</label>
                    <input type="text" class="form-control" name="correo" value="<?= $datos->correo ?>">
                </div>
                <div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Clave</label>
                    <input type="text" class="form-control" name="clave" value="<?= $datos->clave ?>">
                </div>
                <!--<div class="mb-1">
                    <label for="exampleInputEmail1" class="form-label">Tipo de Usuario</label>
                    <select name="opciones">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>-->                
            <?php
            }
        ?>
        <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">Actualizar</button>
    </form>    
</body>
</html>