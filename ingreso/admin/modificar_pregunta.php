<?php
    if (!empty($_POST["btnregistrar"])) {
        if (!empty($_POST["reactivo"]) and !empty($_POST["escala"]) and !empty($_POST["seccion"])) {
            $id = $_POST["idPregunta"];
            $reactivo = $_POST["reactivo"];
            $escala = $_POST["escala"];
            $seccion = $_POST["seccion"];

            $sql = $con -> query("UPDATE preguntas SET reactivo='$reactivo', escala='$escala', seccion='$seccion'  WHERE idPregunta=$id");
            if ($sql==1) {
                header("location: header.php");
            }
            else {
                echo "<div class='alert alert-danger'>Error al modificar pregunta</div>";
            }
        }
        else{
            echo "<div class='alert alert-warning'>Campos vacios</div>";
        }
    }
?>