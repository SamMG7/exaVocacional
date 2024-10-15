<?php
    if (!empty($_POST["btnregistrar"])) {
        if (!empty($_POST["correo"]) and !empty($_POST["clave"])) {
            $id = $_POST["id"];
            $correo = $_POST["correo"];
            $clave = $_POST["clave"];

            $sql = $con -> query("UPDATE usuarios SET correo='$username', clave='$password' WHERE id=$id");
            if ($sql==1) {
                header("location: header.php");
            }
            else {
                echo "<div class='alert alert-danger'>Error al modificar registro</div>";
            }
        }
        else{
            echo "<div class='alert alert-warning'>Campos vacios</div>";
        }
    }
?>