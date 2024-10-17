<?php
  include_once("../conf.php");

  // Crear la conexión 
  $con = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);
  $msj = "";

  // LOGIN: Verificar si se ha enviado el formulario de login
  if (isset($_POST['username'])) {
      // Recibir datos del formulario
      $username = mysqli_real_escape_string($con, $_POST['username']);
      $password = md5(mysqli_real_escape_string($con, $_POST['password']));

      // Consulta para verificar usuario y contraseña juntos
      $sql = "SELECT * FROM usuarios WHERE correo = '$username' AND clave = '$password' AND activo = 'si'";
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
          $msj = "Usuario o contraseña incorrectos.";
      }
  }

  // REGISTRO: Verificar si el formulario de registro fue enviado
  if (!empty($_POST["boton_registro"])) {
      // Verificar que los campos no estén vacíos
      if (!empty($_POST["nombre"]) and !empty($_POST["edad"]) and !empty($_POST["carrera"]) and !empty($_POST["aplicador"])) {
          
          // Asignar los valores del formulario a las variables
          $nombre = $_POST["nombre"];
          $edad = $_POST["edad"];
          $carrera = $_POST["carrera"];
          $aplicador = $_POST["aplicador"];

          // Usar una consulta preparada para insertar los datos de forma segura
          $sql = $con->prepare("INSERT INTO personas (nombre_completo, edad, carrera_interes, nombre_aplicador, fecha_registro) VALUES ($nombre, $edad, $carrera, $aplicador, NOW())");

          // Vincular los parámetros con la consulta preparada (siss = string, int, string, string)
          

          // Ejecutar la consulta y verificar si fue exitosa
          if ($sql->execute()) {
              echo "Registro exitoso.";
          } else {
              echo "Error al registrar: " . $stmt->error;
          }

          // Cerrar el statement
          $sql->close();
      } else {
          echo "Por favor, completa todos los campos.";
      }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login y Registro</title>

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
        .container {
            max-width: 400px;
            width: 100%;
        }
        .tab-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .tab {
            flex: 1;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
            border-bottom: none;
            cursor: pointer;
            background-color: #f0f0f0;
        }
        .tab.active {
            background-color: #007bff;
            color: white;
        }
        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-container input,
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f7f9fc;
        }
        .form-container button {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .register-tab {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Tabs for Login and Register -->
        <div class="tab-container">
            <div class="tab active" id="login-tab">Login</div>
            <div class="tab register-tab" id="register-tab">Registrar</div>
        </div>

        <!-- Formulario de Login -->
        <div class="form-container" id="form-login">
            <h2>Iniciar sesión</h2>
            <form action="index.php" method="POST">
                <label for="username">Correo electrónico:</label>
                <input type="email" name="username" id="username" required>

                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>

                <?php if ($msj != "") { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo $msj; ?>
                    </div>
                <?php } ?>
                
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>

        <!-- Formulario de Registro -->
        <div class="form-container" id="form-register" style="display: none;">
            <h2>Registrar usuario</h2>
            <form action="index.php" method="POST">
                <!-- Campo de Nombre Completo -->
                <label for="full_name">Nombre completo:</label>
                <input name="nombre" type="text" id="full_name" required>

                <!-- Campo de Edad -->
                <label for="age">Edad:</label>
                <input name="edad" type="number" id="age" required min="1" max="120">

                <!-- Campo de Carrera de Interés -->
                <label for="career_interest">Carrera de interés:</label>
                <select name="carrera" id="career_interest" required>
                    <option value="">Selecciona una carrera</option>
                    <option value="Ingeniería en Sistemas">Ingeniería en Sistemas</option>
                    <option value="Medicina">Medicina</option>
                    <option value="Derecho">Derecho</option>
                    <option value="Administración de Empresas">Administración de Empresas</option>
                    <option value="Arquitectura">Arquitectura</option>
                </select>

                <!-- Campo de Nombre de Aplicador -->
                <label for="applicator_name">Nombre del aplicador:</label>
                <select name="aplicador" id="applicator_name" required>
                    <option value="">Selecciona un aplicador</option>
                    <option value="Juan Pérez">Juan Pérez</option>
                    <option value="Ana González">Ana González</option>
                    <option value="Carlos Romero">Carlos Romero</option>
                    <option value="Laura Martínez">Laura Martínez</option>
                </select>

                <button name="boton_registro" type="submit">Registrar</button>
            </form>
        </div>
    </div>

    <script>
        // Scripts para alternar entre Login y Registro
        document.getElementById('login-tab').addEventListener('click', function () {
            document.getElementById('form-login').style.display = 'block';
            document.getElementById('form-register').style.display = 'none';
            this.classList.add('active');
            document.getElementById('register-tab').classList.remove('active');
        });

        document.getElementById('register-tab').addEventListener('click', function () {
            document.getElementById('form-login').style.display = 'none';
            document.getElementById('form-register').style.display = 'block';
            this.classList.add('active');
            document.getElementById('login-tab').classList.remove('active');
        });
    </script>
</body>
</html>

<?php
  mysqli_close($con);
?>
