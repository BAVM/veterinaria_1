<?php
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
                    <img src="../logo.png" alt="Logo Veterinaria">
                    <div class="app-title">Recuperar Contraseña</div> <!-- Título debajo del logo -->
                </div>
                <form id="recoveryForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" placeholder="Nombre de usuario" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                </form>
                <div class="text-center mt-3">
                    <a href="../index.php">Volver al inicio de sesión</a>
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
        document.getElementById("recoveryForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            // Obtener el valor del campo de nombre de usuario
            var username = document.getElementById("username").value.trim(); // Elimina espacios en blanco al inicio y al final

            // Validar que el campo no esté vacío
            if (username === "") {
                alert("Por favor, ingrese su nombre de usuario.");
                return;
            }

            var formData = new URLSearchParams();
            formData.append('nombre_usuario', username);

            // Hacer la petición HTTP al servidor
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $server_url; ?>/recovery.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onload = function() {

                if (xhr.status === 200) {
                    // Si la respuesta es exitosa, consultar el contenido de la respuesta
                    // var response = JSON.parse(xhr.responseText);
                    if (xhr.responseText == "Credenciales enviadas al correo") {
                        // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                        var successMessage = xhr.responseText;
                        document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                        document.getElementById("successMessage").innerText = successMessage;
                        $('#successModal').modal('show');

                        // Redireccionar a index.php cuando se cierre la modal de éxito
                        $('#successModal').on('hidden.bs.modal', function(e) {
                            window.location.href = "../index.php";
                        });
                        // window.location.href = "../index.php";
                    } else {
                        // Si la respuesta indica error, mostrar un mensaje de error
                        // Si hay algún error, muestra un mensaje de error
                        var successMessage = xhr.responseText;
                        document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                        document.getElementById("successMessage").innerText = successMessage;
                        $('#successModal').modal('show');
                    }
                } else {
                    alert("Error al enviar la solicitud de recuperación de contraseña. Por favor, intente nuevamente.");
                }
            };
            xhr.send(formData);
        });
    </script>
</body>

</html>