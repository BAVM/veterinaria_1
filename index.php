<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            margin-top: 5%;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo img {
            width: 350px;
            /* ajusta el tamaño del logo según tus necesidades */
            height: auto;
        }

        .app-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-container">
                <div class="login-logo">
                    <img src="logo.png" alt="Logo Veterinaria">
                    <div class="app-title">MascotasApp</div> <!-- Título debajo del logo -->
                </div>
                <form id="loginForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" placeholder="Nombre de usuario" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </form>
                <div class="text-center mt-3">
                    <a href="views/recovery.view.php">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="text-center mt-3">
                    <span>¿No tienes una cuenta?</span>
                    <a href="views/registrar_usuario.view.php">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="successMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            // Obtener los valores de los campos de entrada
            var username = document.getElementById("username").value.trim(); // Elimina espacios en blanco al inicio y al final
            var password = document.getElementById("password").value.trim();

            // Validar que los campos no estén vacíos
            if (username === "" || password === "") {
                alert("Por favor, complete todos los campos.");
                return;
            }

            var formData = new URLSearchParams();
            formData.append('nombre_usuario', username);
            formData.append('contraseña', password);

            // Hacer la petición HTTP al servidor
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $server_url; ?>/login.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onload = function() {
                if (xhr.status === 200) {

                    if (xhr.responseText == "Inicio de sesión exitoso") {
                        window.location.href = "views/home.view.php";
                    } else {
                        // Si la respuesta indica error, mostrar un mensaje de error
                        // Si hay algún error, muestra un mensaje de error
                        var successMessage = "Usuario y/o Contraseña Errados. Por favor, intente nuevamente.";
                        document.getElementById("successMessage").innerText = successMessage;
                        document.getElementById("successModalLabel").innerText = "Credenciales Inválidas";
                        $('#successModal').modal('show');
                    }
                } else {
                    // Si hay algún error, muestra un mensaje de error
                    alert("Error al enviar la solicitud de recuperación de contraseña. Por favor, intente nuevamente.");
                }
            };
            xhr.send(formData);
        });
    </script>
</body>

</html>