<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Perfiles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .menu {
            display: inline-block;
            text-align: left;
        }
        .menu a {
            display: block;
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            margin: 10px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
        }
        .menu a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Selecciona tu perfil</h2>

    <div class="menu">
        <a href="index.php">Administrador</a>
        <a href="user.php">Usuario</a>
    </div>

</body>
</html>
