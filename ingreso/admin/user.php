<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Usuario</title>

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="number"],
        .login-container input[type="password"],
        .login-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f7f9fc;
            color: #333;
            transition: border-color 0.3s;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="number"]:focus,
        .login-container input[type="password"]:focus,
        .login-container select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .login-container button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .login-container button:hover {
            background-color: #45a049;
        }
        .login-container p {
            margin-top: 15px;
            font-size: 14px;
            color: #888;
        }
        .login-container a {
            color: #4CAF50;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Formulario de Usuario</h2>
    <form action="process_login.php" method="post">
        
        <!-- Campo de Nombre Completo -->
        <label for="full_name">Nombre completo:</label>
        <input type="text" id="full_name" name="full_name" required><br><br>

        <!-- Campo de Edad -->
        <label for="age">Edad:</label>
        <input type="number" id="age" name="age" required min="1" max="120"><br><br>

        <!-- Campo de Carrera de Interés (Lista desplegable) -->
        <label for="career_interest">Carrera de interés:</label>
        <select id="career_interest" name="career_interest" required>
            <option value="">Selecciona una carrera</option>
            <option value="Ingeniería en Sistemas">Ingeniería en Sistemas</option>
            <option value="Medicina">Medicina</option>
            <option value="Derecho">Derecho</option>
            <option value="Administración de Empresas">Administración de Empresas</option>
            <option value="Arquitectura">Arquitectura</option>
        </select><br><br>

        <!-- Campo de Nombre de Aplicador (Lista desplegable) -->
        <label for="applicator_name">Nombre del aplicador:</label>
        <select id="applicator_name" name="applicator_name" required>
            <option value="">Selecciona un aplicador</option>
            <option value="Juan Pérez">Juan Pérez</option>
            <option value="Ana González">Ana González</option>
            <option value="Carlos Romero">Carlos Romero</option>
            <option value="Laura Martínez">Laura Martínez</option>
        </select><br><br>

      
        
        <input type="hidden" name="role" value="user"> <!-- Indica que es usuario -->
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
