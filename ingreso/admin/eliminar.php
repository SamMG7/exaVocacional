<?php

include_once("../conf.php"); // Incluyendo el archivo de configuración o conexión a la base de datos

$con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
if (!empty($_GET["id"])) {
    
    $id = intval($_GET["id"]); 
    
    // Verificar que la conexión a la base de datos esté establecida
    if ($con) {
        // Preparar la consulta para evitar inyección SQL
        $stmt = $con->prepare("DELETE FROM usuarios WHERE id = ?");
        
        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular el parámetro a la consulta
            $stmt->bind_param("i", $id); // "i" indica que es un entero
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo '<div>Persona eliminada correctamente</div>';
                // Redireccionar a una página de confirmación o al listado principal
                header("Location: header.php?mensaje=eliminado");
                exit();
            } else {
                echo '<div>Error al eliminar la persona: ' . $stmt->error . '</div>';
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
